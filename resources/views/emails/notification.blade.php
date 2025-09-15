<!DOCTYPE html>
<html>
<head>
    <title>HappyTails TV</title>
</head>
<body style="background-color: #eeeeee !important;">
    <div style="padding-top: 5% !important;">
        <div style="background-color: white !important; margin: 1% 8% 1% 8% !important; box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19) !important;color: black !important">
            <br>
            <div style="min-width: 100% !important; text-align: -webkit-center !important;">
            <img src="{{url('/happy_tails.jpg')}}" style="width:75px;height:75px">
             <h1 style="color:#39BAE3;font-weight: 900;font-size: 60px;line-height: 1;text-align:center">HappyTails TV</h1>
            </div>  
        
            <div style="text-align: -webkit-center !important; margin-left: 5% !important; margin-right: 5% !important;">
            
            </div>
            <h2 style="text-align:center">{{$mail['title']}}</h2>
            <hr style="margin: 3% 10% 3% 10% !important;">
        
            <div style="padding-right: 10% !important; padding-left: 10% !important;">
                <p>{{$mail['body']}}</p>
            <p>Stay Awesome,<br>The HappyTails TV Team<br></p>
            </div>
            <br>
        </div>
    </div>  
    <br>
    
    <div style="min-width: 100% !important; text-align: -webkit-center !important;">
        <br>
        <p style="text-align: center;color:black !important">Copyright© 2021 HappyTails TV Inc, All rights Reserved.</p>
    </div>
    <br>

</body>
</html>