<?php
// include files
require_once("includes/check-authorize.php");
require_once("includes/functions.php");

$empire_listener = "";
if(isset($_GET['search_listener']))
{
    $search_listener = urldecode($_GET['search_listener']);
    $arr_result = search_listener_name($sess_ip, $sess_port, $sess_token, $search_listener);
    if(array_key_exists("error", $arr_result))
    {
        $empire_listener = "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> ".ucfirst(htmlentities($arr_result["error"]))."</div>";
    }
    else
    {
        if(!empty($arr_result))
        {
            $empire_listener .= '<div class="panel-group"><div class="panel panel-success"><div class="panel-heading">Listener Name: '.htmlentities($arr_result["listeners"][0]["name"]).'</div><div class="panel-body">';
            //Create table for listener details
            $empire_listener .= '<table class="table table-hover table-striped table-bordered table-condensed"><thead><tr><th>Listener Detail</th><th>Detail Value</th></tr></thead><tbody>';
            foreach($arr_result["listeners"][0] as $key => $value)
            {
                if($key != "options")
                {
                    $key = htmlentities($key);
                    $value = htmlentities($value);
                    $empire_listener .= "<tr><td>$key</td><td>$value</td></tr>";
                }
            }
            $empire_listener .= '</tbody></table>';
            //Create table for listener options
            $empire_listener .= '<br><table class="table table-hover table-striped table-bordered table-condensed"><thead><tr><th>Option</th><th>Description</th><th>Required</th><th>Value</th></tr></thead><tbody>';
            foreach($arr_result["listeners"][0]["options"] as $listener_option_name => $listener_option_value_arr)
            {
                $listener_option_name = htmlentities($listener_option_name);
                $listener_opt_desc = htmlentities($listener_option_value_arr["Description"]);
                $listener_opt_req = htmlentities($listener_option_value_arr["Required"] ? 'Yes' : 'No');
                $listener_opt_val = htmlentities($listener_option_value_arr["Value"]);
                $empire_listener .= "<tr><td>$listener_option_name</td><td>$listener_opt_desc</td><td>$listener_opt_req</td><td>$listener_opt_val</td></tr>";
            }
            $empire_listener .= '</tbody></table>';
            $empire_listener .= "</div></div></div>";
        }
        else
        {
            $empire_listener = "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> Unexpected response.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Empire: Search Listener</title>
	<?php @require_once("includes/head-section.php"); ?>
</head>
<body>
    <div class="container">
        <?php @require_once("includes/navbar.php"); ?>
        <br>
        <div class="panel-group">
            <div class="panel panel-primary">
                <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Search Listener by name</div>
                <div class="panel-body">
                    <form role="form" method="get" action="search-listener-name.php" class="form-inline">
                        <div class="form-group">
                            <input type="text" class="form-control" id="search-listener" placeholder="Listener Name" name="search_listener">
                        </div>
                        <button type="submit" class="btn btn-success">Search</button>
                    </form>
                    <br>
                    <?php echo $empire_listener; ?>
                </div>
            </div>
        </div>
        <br>
    </div>
    <?php @require_once("includes/footer.php"); ?>
</body>
</html>
