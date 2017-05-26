<?php
// include files
require_once("includes/check-authorize.php");
require_once("includes/functions.php");

$empire_listener_options = "";
if(isset($_GET['listener_type']))
{
    $empire_listener_options = "<br><b>Listener Options:</b><br>";
    $listener_type = urldecode($_GET['listener_type']);
    $arr_result = get_current_listener_options($sess_ip, $sess_port, $sess_token, $listener_type);
    if(array_key_exists("listeneroptions", $arr_result))
    {
        //Create table for listener options
        $empire_listener_options .= '<br><table class="table table-hover table-striped table-bordered table-condensed"><thead><tr><th>Option</th><th>Description</th><th>Required</th><th>Value</th></tr></thead><tbody>';
        foreach($arr_result["listeneroptions"] as $listener_option_name => $listener_option_value_arr)
        {
            $listener_option_name = htmlentities($listener_option_name);
            $listener_opt_desc = htmlentities($listener_option_value_arr["Description"]);
            $listener_opt_req = htmlentities($listener_option_value_arr["Required"] ? 'Yes' : 'No');
            $listener_opt_val = htmlentities($listener_option_value_arr["Value"]);
            $listener_opt_val = "<div class='form-group'><input type='text' class='form-control' id='$listener_option_name' name='$listener_option_name' value='$listener_opt_val' size='100%'></div>";
            $empire_listener_options .= "<tr><td>$listener_option_name</td><td>$listener_opt_desc</td><td>$listener_opt_req</td><td>$listener_opt_val</td></tr>";
        }
        $empire_listener_options .= '</tbody></table>';
    }
    else
    {
        $empire_listener_options = "<div class='alert alert-danger'>Unexpected response</div>";
    }
}

$empire_create_listener = "";
if(isset($_POST) && !empty($_POST))
{
    $arr_data = array();
    //Remove "CertPath" item from $_POST if it is not set
    //If it exists and listener is of HTTP then it is converted into HTTPS without any error
    if(isset($_POST["CertPath"]) && strlen($_POST["CertPath"])<=0)
    {
        unset($_POST["CertPath"]);
    }
    foreach($_POST as $key => $value)
    {
        $arr_data[$key] = html_entity_decode(urldecode($value));
    }
    $arr_result = create_listener($sess_ip, $sess_port, $sess_token, $arr_data);
    if(array_key_exists("success", $arr_result))
    {
        $empire_create_listener = "<br><br>";
        if($arr_result["success"] == True)
        {
            if(array_key_exists("msg", $arr_result))
            {
                $empire_create_listener .= "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> ".ucfirst(htmlentities($arr_result["msg"]))."</div>";
            }
            else
            {
                $empire_create_listener .= "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> Listener created successfully.</div>";
            }
        }
        else
        {
            if(array_key_exists("msg", $arr_result))
            {
                $empire_create_listener .= "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> ".ucfirst(htmlentities($arr_result["msg"]))."</div>";
            }
            else
            {
                $empire_create_listener .= "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> Listener creation failed.</div>";
            }
        }
    }
    elseif(array_key_exists("error", $arr_result))
    {
        $empire_create_listener .= "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> ".ucfirst(htmlentities($arr_result["error"]))."</div>";
    }
    else
    {
        $empire_create_listener .= "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> Unexpected response.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Empire: Create Listener</title>
	<?php @require_once("includes/head-section.php"); ?>
	<script>
	function get_listener_type_options()
	{
	    var listener_type = document.getElementById("listener-type").value;
        document.location = "create-listener.php?listener_type="+listener_type;
	}
	
	function getParameterByName(name, url)
	{
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
    
    
	</script>
</head>
<body>
    <div class="container">
        <?php @require_once("includes/navbar.php"); ?>
        <br>
        <div class="panel-group">
            <div class="panel panel-primary">
                <div class="panel-heading">Create Listener</div>
                <div class="panel-body">
                    <form role="form" method="post" action="create-listener.php" class="form-inline">
                        <div class="form-group">
                            <select class="form-control" id="listener-type" name="listener-type" onchange="get_listener_type_options()">
                                <option value="">--Listener Type--</option>
                                <option value="dbx">dbx</option>
                                <option value="http">http</option>
                                <option value="http_com">http_com</option>
                                <option value="http_foreign">http_foreign</option>
                                <option value="http_hop">http_hop</option>
                                <option value="meterpreter">meterpreter</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Create</button><?php echo $empire_create_listener; ?>
                        <div id="listener_type_options">
                        <?php echo $empire_listener_options; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
    </div>
    <?php @require_once("includes/footer.php"); ?>
    <script>
    if(getParameterByName("listener_type"))
    {
        document.getElementById("listener-type").value = getParameterByName("listener_type");
    }
    </script>
</body>
</html>
