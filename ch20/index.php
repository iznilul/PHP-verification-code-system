<?php
    require 'controller/CodeController.php';
    session_start();
    if(!empty($_POST['code'])){
        $controller=new CodeController();
        return $controller->checkCode($_POST['code']);
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>Verification code</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js">
    </script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
</head>
<body>
<div class="text-center" style="max-width:300px;margin:30px auto;">
    <h1>verification code system</h1>
    <br>
    <br>
    <div class="form-group">
        <img id="code-image" alt="verification code" src="code.php">
        <a id="refresh" class="btn btn-link btn-block" href="#">change another</a>
    </div>
    <div class="form-group">
        <input class="form-control" type="text" name="code" maxlength="4" placeholder="please input the verification code" />
    </div>
    <div class="form-group">
        <a id="validate" class="btn btn-primary form-control">verify</a>
    </div>
</div>
<script src="js/jquery-3.2.1.js"></script>
<script type="text/javascript">
    $(function(){
        $('#refresh').on('click',function (e) {
            $('#code-image').prop('src','code.php?t='+new Date().getTime())
        });
        $('#validate').on('click',function (e) {
            var code=$('input[name=code]').val();
            if($.trim(code)==''){
                alert("please input the verification code");
                return;
            }
            $.post('index.php',{code:code},function (result) {
                alert(result);
            });
        });
    });
</script>
</body>
</html>
