<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $this->project->title;?> | Project Portfolio</title>
    <style media="screen">
    *{
      margin: 0px;
      padding: 0px;
      box-sizing: border-box;
    }
      body{
        width: 100%;
        height: 100vh;
        background: #7918f2;
        background: -webkit-linear-gradient(-45deg, #ac32e4, #7918f2, #4801ff);
        background: -o-linear-gradient(-45deg, #ac32e4, #7918f2, #4801ff);
        background: -moz-linear-gradient(-45deg, #ac32e4, #7918f2, #4801ff);
        background: linear-gradient(-45deg, #ac32e4, #7918f2, #4801ff);
        color: #fff;
        font-family: sans-serif;
      }
      .flex-box{
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
      }
      .cbox{
        text-align: center;
      }
      h1.heading{
        font-size: 50px;
        line-height: 1.2;
        text-transform: uppercase;
        padding-bottom: 10px;
      }
      .cbox p{
        font-size: 30px;
        color: #fff;
        line-height: 1.2;
      }
      .cbox p:first-letter{
        text-transform: uppercase;
      }
    </style>
  </head>
  <body>
    <div class="flex-box">
      <div class="cbox">
        <h1 class="heading">
          Coming Soon
        </h1>
        <p><?php echo $this->project->title; ?> is under construction</p>
      </div>
    </div>
  </body>
</html>
