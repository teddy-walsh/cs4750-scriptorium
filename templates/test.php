<?php
    $title = "Welcome to Scriptorium";
    $description = 'Welcome to Scriptorium';
    require 'styles/head_style.php';
    ?>

<section>
    <div class="container-fluid fp-script-box">
    	<div class="row">
    		<div class="col-md-3 fp-date">
      			<h5>Date</h5>
  			</div>
  			<div class="col-md-9 fp-genre">
  				<h5>Tags/Genre</h5>
  			</div>
    	</div>
    	<div class="col-md-12 fp-title">
    		<h2>Title</h2>
    	</div>
    	<div class="row">
    		<div class="col-md-12 fp-blurb">
    			<p>Dui urna vehicula tincidunt pretium consequat luctus mi, platea fermentum conubia tempus ac orci. Pellentesque dictum malesuada cubilia faucibus dignissim mi nascetur senectus, augue ad libero efficitur dolor duis lobortis, non etiam sociosqu.</p>
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-3 fp-readmore">
    			<span>Read more ⟶</span>
    		</div>
    		<div class="col-md-9 fp-author">
    			<span><a href="?command=userpage&user=PHP&web=W3schools.com">Test $GET</a></span>
    		</div>
    	</div>
    </div>
</section>


<?php require 'styles/foot_style.php'; ?>		