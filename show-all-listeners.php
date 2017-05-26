<?php
// include files
require_once("includes/check-authorize.php");
require_once("includes/functions.php");

$count = 0;
$empire_listeners = "";
$arr_result = get_all_listeners($sess_ip, $sess_port, $sess_token);
if(!empty($arr_result))
{
    //$empire_listeners = '<table class="table table-hover table-striped"><thead><tr><th>Listener Option</th><th>Listener Value</th></tr></thead><tbody>';
    $empire_listeners .= "<div class='panel-group'>";
    for($i=0; $i<sizeof($arr_result["listeners"]); $i++)
    {
        $empire_listeners .= "<div class='panel panel-success'>
                    <div class='panel-heading'><h4 class='panel-title'><a data-toggle='collapse' href='#collapse$i'>Listener Name: ".htmlentities($arr_result["listeners"][$i]["name"])."</a></h4></div>
                    <div id='collapse$i' class='panel-collapse collapse'>
                        <div class='panel-body'>";
        //Create table for listener details
        $empire_listeners .= '<table class="table table-hover table-striped table-bordered table-condensed"><thead><tr><th>Listener Detail</th><th>Detail Value</th></tr></thead><tbody>';
        foreach($arr_result["listeners"][$i] as $key => $value)
        {
            if($key != "options")
            {
                $key = htmlentities($key);
                $value = htmlentities($value);
                $empire_listeners .= "<tr><td>$key</td><td>$value</td></tr>";
            }
        }
        $empire_listeners .= '</tbody></table>';
        //Create table for listener options
        $empire_listeners .= '<br><table class="table table-hover table-striped table-bordered table-condensed"><thead><tr><th>Option</th><th>Description</th><th>Required</th><th>Value</th></tr></thead><tbody>';
        foreach($arr_result["listeners"][$i]["options"] as $listener_option_name => $listener_option_value_arr)
        {
            $listener_option_name = htmlentities($listener_option_name);
            $listener_opt_desc = htmlentities($listener_option_value_arr["Description"]);
            $listener_opt_req = htmlentities($listener_option_value_arr["Required"] ? 'Yes' : 'No');
            $listener_opt_val = htmlentities($listener_option_value_arr["Value"]);
            $empire_listeners .= "<tr><td>$listener_option_name</td><td>$listener_opt_desc</td><td>$listener_opt_req</td><td>$listener_opt_val</td></tr>";
        }
        $empire_listeners .= '</tbody></table>';
        $empire_listeners .= "</div></div></div>";
        $count = $i + 1;
    }
    $empire_listeners .= "</div>";
}
else
{
    $empire_listeners = "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> Unexpected response.</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Empire: All Listeners</title>
	<?php @require_once("includes/head-section.php"); ?>
</head>
<body>
    <div class="container">
        <?php @require_once("includes/navbar.php"); ?>
        <br>
        <div class="panel-group">
            <div class="panel panel-primary">
                <div class="panel-heading">Show All Listeners (<?php echo $count; ?>)</div>
                <div class="panel-body"><?php echo $empire_listeners; ?></div>
            </div>
        </div>
    </div>
    <?php @require_once("includes/footer.php"); ?>
</body>
</html>
