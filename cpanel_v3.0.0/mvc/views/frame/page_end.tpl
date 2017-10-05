<?php
    $lang   = $data->lang;
    $layout = $data->frame;
    $device = $data->device;
?>

    <!-- End of Page Contents -->

    <div id="hidden" class="hidden" style="display: none;">

        <?php
            if ($layout->cells != null) {
                foreach ($layout->cells as $cell) {
                    $tpl_cell = BASE_DIR . '/mvc/views/hidden/cells/' . $cell . '';
                    include( $tpl_cell );
                }
            }
        ?>

        <?php
            if ($layout->forms != null) {
                foreach ($layout->forms as $form) {
                    $tpl_cell = BASE_DIR . '/mvc/views/hidden/forms/' . $form . '';
                    include( $tpl_cell );
                }
            }
        ?>

        <?php
            if ($layout->previews != null) {
                foreach ($layout->previews as $preview) {
                    $tpl_cell = BASE_DIR . '/mvc/views/hidden/previews/' . $preview . '';
                    include( $tpl_cell );
                }
            }
        ?>

    </div>

    <script>
        //from config to javascript
        var lang           = "<?= Dictionary::get_language(); ?>";
        var pre_path       = "<?= $layout->pre_path; ?>";
        var g_root_url     = "<?= ROOT_URL; ?>";
        var g_base_url     = "<?= BASE_URL; ?>";
        var g_device       = "<?= $device; ?>";
        var g_use_meaningful_url = "<?= USE_MEANINGFUL_URL; ?>";
    </script>
 
    <script type="text/javascript"> function no_error(){return true;}window.onerror = no_error; </script> 

    <?
        if ( $layout->js_files != null ){
            foreach ($layout->js_files as $js_file){
    ?>
    <script type="text/javascript" src="<?= $js_file ?>"></script>
    <?
            } 
        }
    ?>
    
    <!--[if (lte IE 8)]>
    <![endif]-->

    <script type="text/javascript">
        function downloadJSAtOnload() {
            var element = document.createElement("script");
            element.src = g_root_url+"js/front/defer.js";
            document.body.appendChild(element);
        }
        if (window.addEventListener)
            window.addEventListener("load", downloadJSAtOnload, false);
        else if (window.attachEvent)
            window.attachEvent("onload", downloadJSAtOnload);
        else window.onload = downloadJSAtOnload;
    </script>
    
<?/*
//Google Analytics Code *Here*
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50638999-11', 'auto');
  ga('send', 'pageview');

</script>
*/?>

    </body>
</html>

<?php ?>
