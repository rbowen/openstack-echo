<?php

$data = file_get_contents("php://input");
$query = json_decode( $data );
# error_log( print_r( $query, 1 ) );

$me = array(
    'version' => '0.2',
    'name'    => 'OpenStack'
);

$guid = '5c33db4b-91b8-4e40-8765-b8f849de6b68';
$userid = 'AFPPR46VI4HFCERSD2ENKTJBTCGHF6J6ERFIWCEI7GP4YDXFRBEJI';

include('../validate-echo-request-php/valid_request.php');
$valid = validate_request( $guid, $userid );

$help = "Help Message Goes Here";

if ( $valid['success'] )  {

    if ( $query ) {
        $action = $query->request->intent->name;

        if ( $action == "RandomProject" ) {
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

} else {
    error_log( 'Request failed: ' . $valid['message'] );
    die();
}


/*

'Alexa, ask openstack to tell me about a random project'

Returns info about a random openstack project
*/
function randomproject() {

    # Yes, I'm reading the file twice. Sue me.
    $yaml = file('./governance/reference/projects.yaml');
    $projects = array();
    foreach ($yaml as $line) {
        if ( preg_match( '/^\w/', $line ) ) {
            $projects[] = $line;
        }
    }

    $project = $projects[ array_rand( $projects ) ];
    $project = preg_replace( '/:\n/s', '', $project );
    return projectinfo( $project );

}

/*

Returns info about specifed project

'Alexa, tell me about OpenStack Manilla'

*/
function getproject( $query ) {
    $project = $query->request->intent->slots->Project->value;
    return projectinfo( $project );
}

/*

Slurps info about project $project from the yaml file.

*/
function projectinfo( $project ) {
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

