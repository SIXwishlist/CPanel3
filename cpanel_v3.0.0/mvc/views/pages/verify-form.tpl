<?
    $page   =  $data->page;
    $style  =  $data->style;
    
    $countries  =  $data->countries;
?>

<div id="body" style="<?= $style ?>">

    <!--
    <div id="path">
        <div class="cell"> <a href="<?= ROOT_URL ?>home">Home</a> </div>
        <div class="cell"> <a href="<?= ROOT_URL ?>verify-form">Verifying using other options</a> </div>
    </div>
    -->
    
    <h1 id="title" class="">Certificate Advance Search</h1>

    <div id="content">

        <form id="verify_form" class="style1" method="post" action="<?= ROOT_URL ?>certificate" enctype="application/x-www-form-urlencoded">

            <div id="verify_form" class="clearfix">

                <div class="verify_label">Student Name * </div>
                <input name="student_name" type="text" class="verify_input" value="" />

                <div class="clearfix"></div><br />

                <div class="verify_label">Student Number * </div>
                <input name="student_number" type="text" class="verify_input short_input" value="" />

                <div class="clearfix"></div><br />

                <div class="verify_label">Graduation Date * </div>
                <input name="graduation_date" type="text" class="verify_input calender" value="" />

                <div class="clearfix"></div><br />

                <div class="verify_label">Country * </div>

                <!--<input name="country" type="text" class="verify_input" value="" />-->

                <div class="styled-select">
                    <select name="country" class="verify_input">
                        <option value="">Unknown</option>
                    <? 
                        for( $i=0; $i<count($countries); $i++ ){
                            $country = $countries[$i];
                    ?>
                        <option value="<?= $country->code ?>"><?= $country->name ?></option>
                    <?  } ?>
                    </select>
                </div>

                <div class="clearfix"></div><br />

                <input type="submit" value="Verify" />
                <input type="reset"  value="Reset"  />

                <br />

                <!--
                <input type="button" class="verify_button" value="Verify" />

                <br />
                -->

            </div>

            <br /><br />

            <div id="verify_output">
                
            </div>

            <br /><br />

        </form>

    </div>

</div>