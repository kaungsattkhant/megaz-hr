<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'megaz-78046'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */

    'projects' => [
        'megaz-78046' => [

            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */

            // 'credentials' => env('FIREBASE_CREDENTIALS', env('GOOGLE_APPLICATION_CREDENTIALS')),

            'credentials' => [
                "type" => "service_account",
                "project_id" => "megaz-78046",
                "private_key_id" => "54e33f9b4b60f450fd5358e516e7f1b539b8f4f0",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC/gIOr1+ha4WBs\nuz2RN8pannSjRR81o5jETAvwnVphu+p44sKQ8oS1siOzuUnbU3ZecRUYuqaPUTH6\nf45BlWEhg04eb5RxbN5ryQKR+GyIMVgzD7mvSR67wAmASj6x6TRN6TbqkZWbO1Yp\nd5ZIBlXz6JN9cL6IMZwGXkm66Z4xR2JAxm6bv5Xhz+Oe7hlrXDLlSxhWC9NihPmg\nticISDnblQ5KGp8Aye1DjFBZQl/LJiNe7Mu9sGdY6EorF7ce7b7VFXDsLVtEp6WP\ni3uKYcgReEeBlm6YsCBN6agROCGkKMXl5bUjCqsHFgsJ9ZW552ou7YJO3Vl4k2hb\nP+eeE0pFAgMBAAECggEAEfXZI4xH2AIsO3ZiI1UCrvYOhWONtkemI77oHcR8Pg9U\nQcsuqU59gNb4vznhAed1gg0ECAI1bdqVH+PHsUHzzqNUd8lKOEsYHy7GhW8xqqJK\nHdvbM+PdNImhunz8HU58X9RAGMbXq6vofFIhb5bch9gnwQuKaxaQBg7gxl1zvzZD\noU901HJhCBMMdPW7ET1+nzN4X8kii+NAigbM5EopMNFdCBtz0uYe9D1sAwU2A+1V\nrC4zAge37KQ9Ay1hOmAd9fA02D3Wp11GfL6zI9pWiIg9bXGUlUoMYzqsRoCXCL6p\ngRZzW5HwSi8S4EhgkirnC78UxrnY8Z1ornSqv9UzLwKBgQDmkSsURR2D3JKNeYuy\nqqW4xHkXzgFKPUG/pChtGPYn/fbPB7YzELf76DHI4Ktg4OMbUdmEMBc5zSLsBeJr\n8f+PS4058h9tAttFa8xmIvjTj3BSjsr/KcyxLl7M86Uo5DNVak2rYBBxdJmNyMZY\n3CSVM+Rtt6tY8CGoc8RY0RQZIwKBgQDUoDazU7rPrKW1m1lyD9g9amQF2hc5nU40\nSiyvlMWbX4V4x2Fnp29uhSIqqDT+E4r/fc48vEc75m+FcgPGrVfLngRMXbbtxQTL\n2E+c6Lu3xGjLSoI2xi5FcOxmPTZx/8IIg8JV8Cew/2fuwY8pxJsRgyQ22wRI0VEQ\nJeasH0cpdwKBgDZYKxY5AC7vU1+jMWkoTa1SKdSRir5L1+5qq9ijFInykzTS9X/t\nxd/LJ8jYI6zO9NignytIBoFqN3v4GxkkTf8haKUqy3tvO+2N7fx467s5yNi8rV4d\nJRDyBAg8uUX9G5hbWPk2yEllmTfRBec15C2CsKmnq+xnlRdWSgZBKYKPAoGAddGt\nXeMoogkd7ar+fljPTwDUGfYGZpOHESyE5GpAsV7V295HWCOMeKZnD1ITwzJbFEXG\n8Rq3Mjb3Vu+drgrBmn+eDB7lzY0dokLjoqPH2QWJuBQ6YVWhsDLpm6GRuMuq3gL8\nvZgMtmRgWAmDZfaeoq6Tc54bGqp6C8OktX3DdfMCgYEA3GcSIywB67HL6PpH9yYy\n9IV6oUK+BP37o4KIwiNFolUv8iJ5TqFcnHXZJBwtjgmwd3/MaudcQhRaoX6He6+4\nKojBpv+dvI0904wkBipoWfKMvpW2m5sbvZ9C/jg7if1As/jC+tI1N1xs4SmTp9RH\nGVv8Dwa2pyUI7qX86L5elF4=\n-----END PRIVATE KEY-----\n",
                "client_email" => "megaz-ers@megaz-78046.iam.gserviceaccount.com",
                "client_id" => "109077125719348521050",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/megaz-ers%40megaz-78046.iam.gserviceaccount.com",
                "universe_domain" => "googleapis.com"

                // 'type' => 'service_account',
                // 'project_id' => 'shwesankan-f6f4d',
                // 'private_key_id' => '532144d3718f1f914a84660459a7d9e62c9aa3d4',
                // 'private_key' => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC+NOCc7VmpvD9x\nPFEyoF3HqO9bq52miYYTrpVFHDKUvn/y2JA/QPm+/0ovzai0rEbbpQG/NPJy6zkD\nSZhv3x/gBu7ph4WS0oZVJAV+IOMZQ36ozbTDG1hi5QG91gSdaIJVRn+JZdxj/pwp\nt4VyjtKqutXxvKxyhcZ0uJXgXauwDlRn3kGHYOHmQ9BDUxXEz8X2l8YiWfGk+DWr\n4QilLhQus9160X2IIBrsXoGqFYRCcU38X6AunyDGpwRjPSuu+vKjZevFK5LKS8jF\ngooK8KxenumQ+ye1HJWLbTXRUPCF7Ea2Wij7e++2jCPdi0UXyAbPNQdjt1bCOU2A\nHL4iVbavAgMBAAECggEABJF0i5BPxPnWdDvEn3imh/MQvJzkjmljJPneb+h7doMU\nw5pb7Ij89o9fEsLXuG8izIesARGeeRAZMHEGDjnHpDPGbSlqFdqki0rwLtVPXv31\n/svh+YWgm89wOUT4m2c1KiMlUQF3R5eH3KChvdycRqlyFfo4EhAQciE0yEy3oOTM\nhjYXpHCfShUXNESVS+X6raOwBFXYa0ERv0eVL6bIN3U0k28e1quEqLr8F0Oy7eSL\nCh7fnpNcx/iy8smhbbnxIRd3fnWnHzFJn6Sy7QKoX+HLQYus/s7jqrRgWgY+wvA0\nLU/g3Of1oi+GML4EMfODGeAWZNs+5MnNdBgbjORM8QKBgQD68mthvwQETuauD7lx\nBVPcVXCTJsSsBqhlyLCMV+o6EMk+QSIfZ5GLvv+BESWdssywZzDeGKvjUZ+39R/l\nrEmft2kKFUmmO0y3UJl450ZLuw751Billxc/phy50wWXx+Nv5V7gt9bF1QhzLSOE\nyoHcAzSJH+X3HrutKRgjMBewNQKBgQDCCVqAYyfjVPGUezKZSN9bN2AuGYgjSNhE\n7wI4HcdJ+eddFpqBSsk4SRQNkX1eVS1ox0pzIYAxh6eDz+5lqvfFlGOToWInn4T0\nB4/YljvhXUXpVusz+E5ylkNNFkS2jrYI/TYhbXkpiDKiAgS2OSdrTZkCLW6G/iHb\nO8PuYdbv0wKBgEB2c3Upf1un9QPzkBxjVPZ9kCyMweq6zxlOY2O51bZSx49RZfWR\nQkcWqkW6ZOJMot4Rs/Dpf4YmWpQDyMzT5Bm+IKJxc3jbNMrGTpZLOriLdb5haG9p\nD5TPFAP8HCywMVF2J1gsgWRSZBhKGRysdI1S2XT8F7PSVj2WF9g6K2udAoGADvLb\nhxKcixYLOo43OrnzgzqD7WlIJLfC+aSUPjCFHvzixzgrlRRm5jAzHEx+JRtY0T6x\nTKdjEe3KiQwm6KxARHeHKGOBhV3zxz0h5uTEHjuqXCy3GBzkJk3PR4wUpR/fhFfF\nqNOfRwTYKcUQCd3jXI/w3ssTPdEsWJCFKsNc5MMCgYEAh9uvpDgncHqCAiC1LJKt\njyhCzGXAgUe/L0k4WLDkGWlgDwPZ1fT3PCIr04P3UTIGgy5uDmmsTKvOOx7cWe7l\nTHCaQDdtoHKwDNpleeR+PE+aXqnmDsEq4WmW21+yJgm2rHC68NatkovGwhiC06hJ\noL4TGPVUJC634/5NhfQQ1Wk=\n-----END PRIVATE KEY-----\n",
                // 'client_email' => 'shweshankan@shwesankan-f6f4d.iam.gserviceaccount.com',
                // 'client_id' => '101942577395941771354',
                // 'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                // 'token_uri' => 'https://oauth2.googleapis.com/token',
                // 'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
                // 'client_x509_cert_url' => 'https://www.googleapis.com/robot/v1/metadata/x509/shweshankan%40shwesankan-f6f4d.iam.gserviceaccount.com',
                // 'universe_domain' => 'googleapis.com',
            ],


            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firestore Component
             * ------------------------------------------------------------------------
             */

            'firestore' => [

                /*
                 * If you want to access a Firestore database other than the default database,
                 * enter its name here.
                 *
                 * By default, the Firestore client will connect to the `(default)` database.
                 *
                 * https://firebase.google.com/docs/firestore/manage-databases
                 */

                // 'database' => env('FIREBASE_FIRESTORE_DATABASE'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [

                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */

                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */

                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],

            ],

            'dynamic_links' => [

                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */

                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [

                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */

            'http_client_options' => [

                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 *
                 * The default time out can be reviewed at
                 * https://github.com/kreait/firebase-php/blob/6.x/src/Firebase/Http/HttpClientOptions.php
                 */

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [
                    // MyInvokableMiddleware::class,
                    // [MyMiddleware::class, 'static_method'],
                ],
            ],
        ],
    ],
];
