        <!-- app displaying results - - - - - - - - - - - - - - - - -->
        <?php 
            while($row = mysqli_fetch_assoc($searchResult)) {
            // <?php print $row['appId']; 
        ?>
            <div class="accordion" id="accordionExample">
              <div class="card">
                <div class="card-header" id="heading<?php print $row['appId']; ?>">
                  <h2 class="mb-0">
                    <span style="float:right;">
                            <h4>Company</h4>
                            <h4># downloads</h4>
                        </span>
                    <button class="btn btn-link collapsed float-md-left" type="button" data-toggle="collapse" data-target="#collapse<?php print $row['appId']; ?>" aria-expanded="false" aria-controls="collapse<?php print $row['appId']; ?>">
                      <!-- stuff that shows up before dropping down: --> 
                      <span style="float:left;">
                      <img src="./images/Star-icon.png" height="50" width="50" alt="">
                        </span>
                        <span style="float:left;">
                            <h2><?php print "{$row['appName']}" ?></h2>
                        </span>
                    </button>
                  </h2>
                </div>

                <div id="collapse<?php print $row['appId']; ?>" class="collapse" aria-labelledby="heading<?php print $row['appId']; ?>" data-parent="#accordionExample">
                  <div class="card-body">
                    <span style="float:left;">
                     <h2>Description:</h2>
                     <h4><?php print "{$row['appDescription']}"?></h4>
                    </span>
                     <span style="float:right;">  
                     <h3><?php print "{$row['category']}"?></h3>
			         <h3><?php print "{$row['price']}"?></h3>
                     <h3><a class="btn btn-primary" href="https://www.youtube.com/" role="button">More Details</a></h3>                    </span>
                  </div>

                </div>
              </div>
              </div>
        <?php
            }
        ?>
    <!-- end app displaying results -->
