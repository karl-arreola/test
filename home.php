<?php
    $link1 = "http://xomo.ca/x_testing/codeTestPage1.json";
    $link2 = "http://xomo.ca/x_testing/codeTestPage2.json";
    $link3 = "http://xomo.ca/x_testing/codeTestPage3.json";
    $link4 = "http://xomo.ca/x_testing/codeTestPage4.json";

    $events1 = parseJson($link1);
    $events2 = parseJson($link2);
    $events3 = parseJson($link3);
    $events4 = parseJson($link4);
    
    function findTotal($event, $categoryName) {
        $ticketClasses = $event->{'ticket_classes'};
        $total = 0;

        foreach ($ticketClasses as $ticketClass) {
            if(isset($ticketClass->{$categoryName})) {
                $total += $ticketClass->{$categoryName};
            }
        }

        return $total;
    }

    function findTicketType($capacity, $sold) {
        $ticketLeftover = $capacity - $sold;
        $ticketType = "";

        if($ticketLeftover >= 10) {
            $ticketType = "BUY"; 
        } else if ($ticketLeftover == 0) {
            $ticketType = "SOLD OUT"; 
        } else {
            $ticketType = "RUSH";
        }

        return $ticketType;
    }

    function parseJson($link) {
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
        
        $json = curl_exec($ch);

        if(!$json) {
            debug("Failed to read json file" . curl_error($ch));
        } else {
            debug("json file read successfully");
        }

        $obj = json_decode($json);
        
        if(isset($obj->{'events'})) {
            $events = $obj->{'events'};
            //$eventCount = 0;
            $newEvents = array();

            foreach ($events as $event) {
                if(isset($event->{'id'})){
                    $id = $event->{'id'};
                } else {
                    $id = "";
                }

                if(isset($event->{'url'})){
                    $url = $event->{'url'};
                } else {
                    $url = "";
                }

                if(isset($event->{'name'}->{'text'})){
                    $name = $event->{'name'}->{'text'};
                } else {
                    $name = "";
                }

                if(isset($event->{'ticket_classes'})){
                    $capacity = findTotal($event, 'quantity_total');
                    $sold = findTotal($event, 'quantity_sold');
                    $ticketType = findTicketType($capacity, $sold);
                } else {
                    $sold = "";
                    $ticketType = "";
                    $capacity = "";
                }

                $eventArray = array(
                    'id' => $id,
                    'url' => $url,
                    'name' => $name,
                    'capacity' => $capacity,
                    'sold' => $sold,
                    'ticketType' => $ticketType
                );

                array_push($newEvents, $eventArray);
            }

            curl_close($ch);

            return $newEvents;

        } else {
            debug("error from " . $link);
        }
    }

    function debug($msg) {
       $msg = str_replace('"', '\\"', $msg); // Escaping double quotes 
        echo "<script>console.log(\"$msg\")</script>";
    }

    function listEventsData($events) {
        if(isset($events)) {
            //initiate table with headers
            $eventsData = "<table><tr><th>Events</th><th>Status</th></tr>";

            //table contents
            foreach($events as $event) {
                //omit row for event if name or ticketType not set
                if(isset($event["name"]) && isset($event["ticketType"])) {
                    $eventsData .= "<tr><td>" . $event["name"] . "</td><td>";

                    if($event["ticketType"] == "BUY" || "RUSH") {
                        $eventsData .= "<a href='" . $event["url"] . "' target='_blank'><div class='buyButton'>". $event["ticketType"] ."</div>";
                    } else {
                        $eventsData .= $event["ticketType"];
                    }

                    $eventsData .= "</td></tr>";
                }
            }

            //close table
            $eventsData .= "</table>";

            echo $eventsData;
        } else {
            echo "Page could not be loaded!";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- CSS -->
    <link href="/css/home.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <!-- Scripts --> 
    <script src="/scripts/homeFunctions.js" type="text/javascript"></script>
</head>
<body>
    <div class="navbar navbar-default">
        API Test
    </div>

    <div class="pages">
        <div class="page1Button" onclick="openClose('page1')">Page 1</div>
        <div class="page2Button" onclick="openClose('page2')">Page 2</div>
        <div class="page3Button" onclick="openClose('page3')">Page 3</div>
        <div class="page4Button" onclick="openClose('page4')">Page 4</div>
    </div>

    <div class="eventsTable">
        <div id="page1">
            <?php listEventsData($events1); ?>
        </div>
        <div class="divClose" id="page2">
            <?php listEventsData($events2); ?>
        </div>
        <!--<div class="divClose" id="page3">
            <?php listEventsData($events3); ?>
        </div>-->
        <div class="divClose" id="page4">
            <?php listEventsData($events4); ?>
        </div>
    </div>
</body>
</html>