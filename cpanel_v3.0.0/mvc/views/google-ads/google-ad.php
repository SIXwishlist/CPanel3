<div id="AdsGroup2">
</div>

<div id="GoogleAds2" class="ads_group right_border">

    <div id="google-ads-2"></div>
    <script type="text/javascript">

        /* Calculate the width of available ad space */
        ad = document.getElementById('google-ads-2');

        /* Replace ca-pub-XXX with your AdSense Publisher ID */
        google_ad_client = "ca-pub-XXX";
        //google_ad_client = "ca-pub-4225399113059560";

        /* Replace 1234567890 with the AdSense Ad Slot ID */
        google_ad_slot = "1234567890";
        //google_ad_slot = "XXXXXXXXXX";
        //google_ad_slot = "2914172734";

        /* Do not change anything after this line */
        //var ww = $(window).width();
        var w = (window.innerWidth > 0) ? window.innerWidth : screen.width;

        if (w > '1023') {
            google_ad_size = ["160", "600"];//160 x 600
        } else if (w >= '768') {
            google_ad_size = ["160", "600"];//160 x 600
            //} else if ( w <= '480' && w >= '300' ) {
        } else {
            google_ad_size = ["234", "60"];//234 x 60
        }

        document.write(
                '<ins class="adsbygoogle" style="display:inline-block;width:'
                + google_ad_size[0] + 'px;height:'
                + google_ad_size[1] + 'px" data-ad-client="'
                + google_ad_client + '" data-ad-slot="'
                + google_ad_slot + '"></ins>'
                );
        (adsbygoogle = window.adsbygoogle || []).push({});

    </script>
    <script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

</div>