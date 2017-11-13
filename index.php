<?php
require_once 'core/init.php';

$user = new User();
if($user->isLoggedIn()){
  $user_permission = $user->data()->permission;
  Redirect::to($user_permission);

  if($user->hasPermission('moderator')){
    $moderator = '<p>You are an moderator</p>';
  }

}
include_once 'includes/overall/overall_header.php';
?>
<title>CBT | Home</title>


<div style="position: absolute;" class="col-xs-12">
<br>
<br>
<?php
Character::flash('home');
?>
</div>

<?php
		echo $privillege = isset($moderator) ? $moderator: ' ';
?>

<div id="carousel-example-generic" class="carousel slide full"  data-interval="3000" data-ride="carousel" style="max-height: 800px; position: relative;">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
    <li data-target="#carousel-example-generic" data-slide-to="3"></li>
    <li data-target="#carousel-example-generic" data-slide-to="4"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="images/i0.jpg" class="img-responsive col-xs-12" height="800">
      <div class="carousel-caption">
        
      </div>
    </div>
    <div class="item">
      <img src="images/i1.jpg" class="img-responsive col-xs-12" height="800">
      <div class="carousel-caption">
        
      </div>
    </div>
     <div class="item">
      <img src="images/i2.jpg" class="img-responsive col-xs-12" height="800">
      <div class="carousel-caption">
        
      </div>
    </div>
     <div class="item">
      <img src="images/i3.jpg" class="img-responsive col-xs-12" height="800">
      <div class="carousel-caption">
        
      </div>
    </div>
     <div class="item">
      <img src="images/i4.jpg" class="img-responsive col-xs-12" height="800">
      <div class="carousel-caption">
        
      </div>
    </div>
    
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
</div>

<?php include_once 'includes/overall/overall_footer.php';?>