<?php

/*  Remove query strings from CSS and JS inclusions  */
function _remove_script_version($src) {
   $parts = explode('?ver', $src);
   return $parts[0];
}
add_filter('style_loader_src', '_remove_script_version', 15, 1);
add_filter('script_loader_src', '_remove_script_version', 15, 1);


/*  Force scripts to load asynchronously for better performance */
function add_async_attribute($tag, $handle) {
	$scripts_to_async = array('script-handle', 'another-handle');
	foreach($scripts_to_async as $async_script) {
	  	if ($async_script !== $handle) return $tag;
	  	return str_replace(' src', ' async="async" src', $tag);
	}
	return $tag;
}
add_filter('script_loader_tag', 'add_async_attribute', 10, 2);

/*  Force scripts to defer for better performance  */
function add_defer_attribute($tag, $handle) {
    
    $scripts_to_defer = array('script-handle', 'another-handle');
 	foreach($scripts_to_defer as $defer_script) {
  		if ($async_script !== $handle) return $tag;
      	return str_replace(' src', ' defer="defer" src', $tag);
 	}
    return $tag;
}
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);

/* Remove jquery migrate for enhanced performance */
function remove_jquery_migrate($scripts) {
   if (is_admin()) return;
   $scripts->remove('jquery');
   $scripts->add('jquery', false, array('jquery-core-js'), '1.12.4');
}
add_action('wp_default_scripts', 'remove_jquery_migrate');


/* ---------------------------
/* START HTML MINIFY 
/ ----------------------------*/
class HTML_Minify
{
   // Settings
   protected $compress_css;
   protected $compress_js;
   protected $info_comment;
   protected $remove_comments;
   protected $shorten_urls;

   // Variables
   protected $html = '';

   public function __construct($html, $compress_css=true, $compress_js=false, $info_comment=false, $remove_comments=true, $shorten_urls=true)
   {
      if ($html !== '')
      {
         $this->compress_css = $compress_css;
         $this->compress_js = $compress_js;
         $this->info_comment = $info_comment;
         $this->remove_comments = $remove_comments;
         $this->shorten_urls = $shorten_urls;

         $this->html = $this->minifyHTML($html);

         if ($this->info_comment)
         {
            $this->html .= "\n" . $this->bottomComment($html, $this->html);
         }
      }
   }

   public function __toString()
   {
      return $this->html;
   }

   protected function bottomComment($raw, $compressed)
   {
      $raw = strlen($raw);
      $compressed = strlen($compressed);

      $savings = ($raw-$compressed) / $raw * 100;

      $savings = round($savings, 2);

      return '<!--Bytes before:'.$raw.', after:'.$compressed.'; saved:'.$savings.'%-->';
   }

   protected function callback_HTML_URLs($matches)
   {
      // [2] is an attribute value that is encapsulated with "" and [3] with ''
      $url = (!isset($matches[3])) ? $matches[2] : $matches[3];

      return $matches[1].'="'.absolute_to_relative_url($url).'"';
   }

   protected function minifyHTML($html)
   {
      $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';

      if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER) === false)
      {
         // Invalid markup
         return $html;
      }

      $overriding = false;
      $raw_tag = false;

      // Variable reused for output
      $html = '';

      foreach ($matches as $token)
      {
         $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

         $content = $token[0];

         $relate = false;
         $strip = false;

         if (is_null($tag))
         {
            if ( !empty($token['script']) )
            {
               $strip = $this->compress_js;

               // Will still end up shortening URLs within the script, but should be OK..
               // Gets Shortened:   test.href="http://domain.com/wp"+"-content";
               // Gets Bypassed:    test.href = "http://domain.com/wp"+"-content";
               $relate = $this->compress_js;
            }
            else if ( !empty($token['style']) )
            {
               $strip = $this->compress_css;

               // No sense in trying to relate at this point because currently only URLs within HTML attributes are shortened
               //$relate = $this->compress_css;
            }
            else if ($content === '<!--wp-html-compression no compression-->')
            {
               $overriding = !$overriding;

               // Don't print the comment
               continue;
            }
            else if ($this->remove_comments)
            {
               if (!$overriding && $raw_tag !== 'textarea')
               {
                  // Remove any HTML comments, except MSIE conditional comments
                  $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);

                  $relate = true;
                  $strip = true;
               }
            }
         }
         else  // All tags except script, style and comments
         {
            if ($tag === 'pre' || $tag === 'textarea')
            {
               $raw_tag = $tag;
            }
            else if ($tag === '/pre' || $tag === '/textarea')
            {
               $raw_tag = false;
            }
            else if (!$raw_tag && !$overriding)
            {
               if ($tag !== '')
               {
                  if (strpos($tag, '/') === false)
                  {
                     // Remove any empty attributes, except:
                     // action, alt, content, src
                     $content = preg_replace('/(\s+)(\w++(?<!action|alt|content|src)=(""|\'\'))/i', '$1', $content);
                  }

                  // Remove any space before the end of a tag (including closing tags and self-closing tags)
                  $content = preg_replace('/\s+(\/?\>)/', '$1', $content);

                  // Do not shorten canonical URL
                  if ($tag !== 'link')
                  {
                     $relate = true;
                  }
                  else if (preg_match('/rel=(?:\'|\")\s*canonical\s*(?:\'|\")/i', $content) === 0)
                  {
                     $relate = true;
                  }
               }
               else  // Content between opening and closing tags
               {
                  // Avoid multiple spaces by checking previous character in output HTML
                  if (strrpos($html,' ') === strlen($html)-1)
                  {
                     // Remove white space at the content beginning
                     $content = preg_replace('/^[\s\r\n]+/', '', $content);
                  }
               }

               $strip = true;
            }
         }

         // Relate URLs
         if ($relate && $this->shorten_urls)
         {
            $content = preg_replace_callback('/(action|data|href|src)=(?:"([^"]*)"|\'([^\']*)\')/i', array(&$this,'callback_HTML_URLs'), $content);
         }

         if ($strip)
         {
            $content = $this->removeWhiteSpace($content, $html);
         }

         $html .= $content;
      }

      return $html;
   }

   protected function removeWhiteSpace($html, $full_html)
   {
      $html = str_replace("\t", ' ', $html);
      $html = str_replace("\r", ' ', $html);
      $html = str_replace("\n", ' ', $html);

      // This is over twice the speed of a RegExp
      while (strpos($html, '  ') !== false)
      {
         $html = str_replace('  ', ' ', $html);
      }

      return $html;
   }
}

function html_minify_buffer($html)
{
   // Duplicate library may be in use elsewhere
   if (!function_exists('absolute_to_relative_url'))
   {
      require_once 'absolute-to-relative-urls.php';
     
   }

   return new HTML_Minify($html);
}
$wp_html_compression_run = false;

function wp_html_compression_start()
{
   global $wp_html_compression_run;

   if (!$wp_html_compression_run)
   {
      $wp_html_compression_run = true;

      // "Humans TXT" plugin support
      $is_humans = (!function_exists('is_humans')) ? false : is_humans();

      if (!$is_humans && !is_feed() && !is_robots())
      {
         ob_start('html_minify_buffer');
      }
   }
}

// Prevents errors when this file is accessed directly
if (function_exists('is_admin'))
{
   if (!is_admin())
   {
      add_action('template_redirect', 'wp_html_compression_start', -1);

      // In case above fails (it does sometimes.. ??)
      add_action('get_header', 'wp_html_compression_start');
   }
   else
   {
      // For v0.6
      //require_once dirname(__FILE__) . '/admin.php';
   }
}