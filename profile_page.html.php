<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
	    <link rel="stylesheet" href="style.css"/>
        <title><?php htmlout("learn3 - " . $username); ?></title>
        <style>
            #side-menu {
                /*background: linear-gradient(<?php echo $gradientString ?>);*/
            }

            .user-img {
                background-image: url("<?php echo 'img/' . $userpic ?>");
                width: 200px;
                height: 200px;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: 50% 50%;
                display: inline-block;
                box-shadow: 0px 0px 5px #fff;
                border: 2px solid #fff;
                border-radius: 100px;
            }
        </style>
	</head>

	<body>
        <!--Side menu-->
        <nav id="side-menu">   
            <div id="logo">learn3</div>

            <!--<form action="general_search.php" method="get">-->

                <input name="search" id="search-field" type="text" placeholder="Search..." />
                <!--<input type="submit">-->
                <div class="divisor"></div>
            

            <div id="user-profile-container">
                <div class="user-img"></div>
                <div id="user-name" class="black-font"><?php htmlout($fullname); ?></div>
                <div id="user-trackers" class="black-font">Trackers 1000</div>
                <div id="user-tracking" class="black-font">Tracking 1000</div>
                <div id="user-description" class="black-font"><?php htmlout($userdesc); ?></div>
            </div>
            <div id="search-results-container"></div>

            <footer>
                <div id="pta" class="black-font">Privacy | Terms | About</div>
                <div id="copyright" class="black-font">Copyright 2015 - Learn3</div>
            </footer>
        </nav>

        <!--General Content - Generated by PHP-->
        <section id="general-content">
            <!--<div class="button-save">Save Positions</div>-->
        </section>
		<script src="jquery/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="d3/d3.v3.min.js"></script>
        <script>
            
            var nodesObjs = <?php echo $nodesJson; ?>;

            var linksObjs = <?php echo $linksJson; ?>;

            var userProfile = $("#user-profile-container");

            var searchField = $("#search-field");

            var searchResults = $("#search-results-container");
            searchResults.hide();

            var searchContainer = d3.select("#search-results-container");

            var searchTimeout = null;

            searchField.on("keydown", function(event) {
                if(searchTimeout)
                    clearTimeout(searchTimeout);

                //Must set timeout to give time for the field register its new value and avoid too many ajax requests
                searchTimeout = setTimeout(function() { 
                    searchTimeout = null;
                    onSearch(searchField.val(), event.keyCode);
                }, 500);    

            })
            //.on("focus", onSearchFocus)
            //.on("focusout", onSearchFocusOut)
            ;

            function onSearchFocus() {
                userProfile.hide();
            }

            function onSearchFocusOut() {
                userProfile.show();
            }

            function onSearch(fieldValue, lastKey) {

                if(fieldValue == "") {
                    userProfile.show(); //show de user profile container
                    searchResults.hide();   //Hide the search results container
                    searchResults.empty();  //Remove all search results childs
                    return;
                }

                userProfile.hide();
                searchResults.show();                

                $.get("./", { search_query: fieldValue }, function(result) {

                    var resultsArray = JSON.parse(result);

                    //Remove all search results    
                    searchContainer.selectAll("*").remove();

                    if(resultsArray.length < 1) {
                        searchContainer.append("div")
                            .classed("search-noresult", true)  
                            .text("No results.");

                        return;
                    }

                    searchContainer.selectAll(".search-result").data(resultsArray).enter()
                        .append("div")
                        .classed("search-result", true)
                        .text(function(d) { return d.name; });
  
                    console.log(resultsArray);         
                });     
            }

        </script>
		<script type="text/javascript" src="main.js"></script>
	</body>
</html>