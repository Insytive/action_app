<?php
    define('PANACEA_API', 'https://api.panaceamobile.com/json/3');
    define('PANACEA_USERNAME', 'kylemabaso');
    define('PANACEA_API_KEY', 'flfyrcm1_ifuvvw-mfp6el7u0bnv5mtd0t5_b_2cg6rwmz47');

    define('HIGHEST_BIT', 16384);
    define('MEMBER_ACTIVE', 1);
    define('MEMBER_VOLUNTEER', 2);
    define('MEMBER_SUPPORTER', 4);
    define('MEMBER_VOLUNTEER_REVIEW', 8);

    define('MEMBER_NUMBER_PREFIX', 'ASA');

    return [
        'AsaUserStatus' => [
            MEMBER_ACTIVE => 'Active (Member)',
            MEMBER_VOLUNTEER => 'Volunteer',
            MEMBER_SUPPORTER => 'Supporter',
            MEMBER_VOLUNTEER_REVIEW => 'Volunteer Review'
        ]
    ];
