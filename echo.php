<?php

$data = file_get_contents("php://input");
$query = json_decode( $data );
# error_log( print_r( $query, 1 ) );

$me = array(
    'version' => '0.1',
    'name'    => 'OpenStack'
);

$help = "Help Message Goes Here";

if ( $query ) {
    $action = $query->request->intent->name;

    if ( $action == "RamdomProject" ) {
        $response = randomproject();
    }

    elseif ( $action == "GetProject" ) {
        $response = getproject( $query );
    }

    elseif ( $action = 'GetHelp' ) {
        $response = $help;
    }

    else {
        $response = $help;
    }

    sendresponse( $response, $me );
} else {
    sendresponse( $help, $me );
}

/*
Return a random project name
*/
function randomproject() {
    return "Sorry, that function isn't implemented yet. Check back in a day or two.";
}

function getproject( $query ) {
    $project = $query->request->intent->slots->Project->value;
    $yaml = file_get_contents('./governance/reference/projects.yaml');

    # Yes, I know this is the wrong way to parse YAML. The various
    # extensions are giving me fits.
    $yaml = preg_replace('/^.*\n' . $project . ':.+?mission: >/is', '', $yaml );
    $yaml = preg_replace('/\n  url:.*$/is', '', $yaml );

    return $project . 's mission is ' . $yaml;
}

/*

Format and return the response back to Alexa

*/
function sendresponse( $response, $me ) {

    $response = array (
       "version" => $me['version'],
        'response' => array (
            'outputSpeech' => array (
                'type' => 'PlainText',
                'text' => $response
            ),

             'card' => array (
                   'type' => 'Simple',
                   'title' => $me['name'],
                   'content' => $response
             ),

            'shouldEndSession' => 'true'
        ),
    );

    echo json_encode($response);
}

?>

