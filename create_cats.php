<?php
require_once('wp-load.php');

$categories = [
    'Company Updates',
    'Employee & Culture',
    'Fun & Engagement Events',
    'Learning & Development',
    'Recruitment',
    'Community',
    'Workplace Information',
    'Recognition',
    'Industry & Insights',
    'Media'
];

foreach ($categories as $cat) {
    if (!term_exists($cat, 'category')) {
        wp_insert_term($cat, 'category');
        echo "Created: " . $cat . "\n";
    } else {
        echo "Exists: " . $cat . "\n";
    }
}
echo "Finished creating categories.\n";
