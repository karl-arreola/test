<?php
    $link1 = "http://xomo.ca/x_testing/codeTestPage1.json";
    $link2 = "http://xomo.ca/x_testing/codeTestPage2.json";
    $link3 = "http://xomo.ca/x_testing/codeTestPage3.json";
    $link4 = "http://xomo.ca/x_testing/codeTestPage4.json";

    parseJson($link1);
    
    function findTotal($event, $categoryName) {
        $ticketClasses = $event->{'ticket_classes'};
        $total = 0;

        foreach ($ticketClasses as $ticketClass) {
            $total += $ticketClass->{$categoryName};
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
        $events = $obj->{'events'};
        //$eventCount = 0;
        $newEvents = [];

        foreach ($events as $event) {
            $id = $event->{'id'};
            $url = $event->{'url'};
            $name = $event->{'name'}->{'text'};
            $capacity = findTotal($event, 'quantity_total');
            $sold = findTotal($event, 'quantity_sold');
            $ticketType = findTicketType($capacity, $sold);
        }

        curl_close($ch);
    }

    function debug($msg) {
       $msg = str_replace('"', '\\"', $msg); // Escaping double quotes 
        echo "<script>console.log(\"$msg\")</script>";
    }

    function listEventsData($events) {
        foreach($events as $event) {
            echo "<div>" . $event->name . "</div>";
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
</head>
<body>
    <div class="navbar navbar-default">
        API Test
    </div>
    <div class="eventsTable">
        <div class="events">
            <div class="title">Events</div>
        </div>
        <div class="status">
            <div class="title">Status</div>
        </div>
    </div>
</body>
</html>