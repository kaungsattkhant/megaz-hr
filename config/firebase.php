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
                // "type" => "service_account",
                // "project_id" => "megaz-78046",
                // "private_key_id" => "54e33f9b4b60f450fd5358e516e7f1b539b8f4f0",
                // "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC/gIOr1+ha4WBs\nuz2RN8pannSjRR81o5jETAvwnVphu+p44sKQ8oS1siOzuUnbU3ZecRUYuqaPUTH6\nf45BlWEhg04eb5RxbN5ryQKR+GyIMVgzD7mvSR67wAmASj6x6TRN6TbqkZWbO1Yp\nd5ZIBlXz6JN9cL6IMZwGXkm66Z4xR2JAxm6bv5Xhz+Oe7hlrXDLlSxhWC9NihPmg\nticISDnblQ5KGp8Aye1DjFBZQl/LJiNe7Mu9sGdY6EorF7ce7b7VFXDsLVtEp6WP\ni3uKYcgReEeBlm6YsCBN6agROCGkKMXl5bUjCqsHFgsJ9ZW552ou7YJO3Vl4k2hb\nP+eeE0pFAgMBAAECggEAEfXZI4xH2AIsO3ZiI1UCrvYOhWONtkemI77oHcR8Pg9U\nQcsuqU59gNb4vznhAed1gg0ECAI1bdqVH+PHsUHzzqNUd8lKOEsYHy7GhW8xqqJK\nHdvbM+PdNImhunz8HU58X9RAGMbXq6vofFIhb5bch9gnwQuKaxaQBg7gxl1zvzZD\noU901HJhCBMMdPW7ET1+nzN4X8kii+NAigbM5EopMNFdCBtz0uYe9D1sAwU2A+1V\nrC4zAge37KQ9Ay1hOmAd9fA02D3Wp11GfL6zI9pWiIg9bXGUlUoMYzqsRoCXCL6p\ngRZzW5HwSi8S4EhgkirnC78UxrnY8Z1ornSqv9UzLwKBgQDmkSsURR2D3JKNeYuy\nqqW4xHkXzgFKPUG/pChtGPYn/fbPB7YzELf76DHI4Ktg4OMbUdmEMBc5zSLsBeJr\n8f+PS4058h9tAttFa8xmIvjTj3BSjsr/KcyxLl7M86Uo5DNVak2rYBBxdJmNyMZY\n3CSVM+Rtt6tY8CGoc8RY0RQZIwKBgQDUoDazU7rPrKW1m1lyD9g9amQF2hc5nU40\nSiyvlMWbX4V4x2Fnp29uhSIqqDT+E4r/fc48vEc75m+FcgPGrVfLngRMXbbtxQTL\n2E+c6Lu3xGjLSoI2xi5FcOxmPTZx/8IIg8JV8Cew/2fuwY8pxJsRgyQ22wRI0VEQ\nJeasH0cpdwKBgDZYKxY5AC7vU1+jMWkoTa1SKdSRir5L1+5qq9ijFInykzTS9X/t\nxd/LJ8jYI6zO9NignytIBoFqN3v4GxkkTf8haKUqy3tvO+2N7fx467s5yNi8rV4d\nJRDyBAg8uUX9G5hbWPk2yEllmTfRBec15C2CsKmnq+xnlRdWSgZBKYKPAoGAddGt\nXeMoogkd7ar+fljPTwDUGfYGZpOHESyE5GpAsV7V295HWCOMeKZnD1ITwzJbFEXG\n8Rq3Mjb3Vu+drgrBmn+eDB7lzY0dokLjoqPH2QWJuBQ6YVWhsDLpm6GRuMuq3gL8\nvZgMtmRgWAmDZfaeoq6Tc54bGqp6C8OktX3DdfMCgYEA3GcSIywB67HL6PpH9yYy\n9IV6oUK+BP37o4KIwiNFolUv8iJ5TqFcnHXZJBwtjgmwd3/MaudcQhRaoX6He6+4\nKojBpv+dvI0904wkBipoWfKMvpW2m5sbvZ9C/jg7if1As/jC+tI1N1xs4SmTp9RH\nGVv8Dwa2pyUI7qX86L5elF4=\n-----END PRIVATE KEY-----\n",
                // "client_email" => "megaz-ers@megaz-78046.iam.gserviceaccount.com",
                // "client_id" => "109077125719348521050",
                // "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                // "token_uri" => "https://oauth2.googleapis.com/token",
                // "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                // "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/megaz-ers%40megaz-78046.iam.gserviceaccount.com",
                // "universe_domain" => "googleapis.com"
                "type" => "service_account",
                "project_id" => "megaz-78046",
                "private_key_id" => "d6b018aa4a4f72e2fadef885afe387f24e4fe093",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCMbweeEpmDNh33\nikVk2+nDUnjrbZ/8lJ/uFHKjt6ZoY4pCSttTZ8+jsSJZWlOCLWnwwTcdEpD9rXD5\noU+8kmVHsxn+zx0KQmeAcM3vugeJwKQChnjq1iL1Tty9o9d8d1qnZPVmUxhpdhK2\nRGGT98+Nwtv7g+svuvwW/AUWpKu41s0JnJC59A/rdWw2C9oiR4N5lFewLLVW2OH7\nGvaxpBP1dwrs9rlIj+BBtaTFu8v2c7jjAKfVwcUqCLI9Bl+3LBslnT3HC3dIaQAA\n5wzcoFhaiQTPymdgMwwO/yCEub+VQ+itawk4bciZQ1Rx0s5FMVccZcQEsTa6UrVv\nJoxTjI0BAgMBAAECggEAAOtS8rTT/iJ9gOHZ9kb4rFP901rPrJQYiZbu9NubV2L9\nyiUkQzsUbkGjBigs0FsHtssVO0GgZw5woSvuQCuCNe1cFmeCsOsMM1xrquvYE+Gl\nyi/hHqaGhrYCnKu77dBIQMTYzM/W1sWCcgCzvb+yNz8aFH73pBergdsSHYTAFHH6\nJMicOVotahiP6uAKWyayLSRQe6xx0dOMz88F0snJYDsKc4nvrzS5ooDqtYP5jNBK\npuAChA91Om+/alc5UZRE2e43NN8Phrstw8jQRgGbpbpzShYp9ZuCWiwl7TxL7pJS\nC3WNNqpbLn536K0eS/TxD0R9/5FtjM86a1RyEgfhQQKBgQC/Nn2dp7iBoaky1kop\nE2YabBpDZDlQhEXN0jCVHpKb25In0GjDO+tpZDkcLb+7OOkHe0i9un18L4DvhY2L\nXdyPffbM0dDZsDcT9lSImlX6KQPaCgihF9i+7mZxoH1yH2A8jNUcKkhL5seQKp1J\n5IIvN7p2Ab4RtqnXTfTNLYvZwQKBgQC8BAi5mYNfDktc6MQGTILokQ7shFAeg4af\nd0rPMUPHVESAmVDuPBBqAqHALeAvCk0R/8uZF4sW+qzZFsbmf+rM1ZHm3kyp0l5T\ntnUN8BoRwcGUCn708DDs219J4C0/E71kSx8rq1T+H+iOTnJ3xd6iiseMTKy/9upA\n3irqXL0DQQKBgEDaZniWbknpXs3TSIvt/G9xwuagk5/vfQ12AOxS1j7ulf/S+/Vb\n2ViBma0pByKlBsR22BEhs3XvVrfmgD+Iskoo2xBVCZUL42Jt5fX/ArI6Pwgw5wRc\nZjM8AJsBaArRAX8H9S/8V1EHESCgk4CAdHc3W5KyvJidb7WKx0Df9dMBAoGAGL2v\nKD07PFb1M9Stdua6k0ADZrxCjsFgBbv87CbfdGtyWDnUnITq5AhUoKVoysfgPG4c\nrJichPmXpTmKEuriSvIPsQGvtOkp4HoyDRN7WrrUblazPigdSA3WDDKjwZknYvs/\nrs9s78PjJlbMWVaAZYM9nwi38srbKI4Tt6NcfAECgYEAmPt0VyvikeDeu4u2w0LD\ndsUo5+HmzfxXcGIIdFESFOrugGl9ir6cy+IhudHp/P/tGO+g2eO8Flk/L7F1U1f+\nC3QgdTabywtsEXQbzDRocNTyMjx0m+cH+zi1SU+54h1V8Fgqy390EWzxAqPzbFT/\nxE3VI3YceEGr+w2SdEey9Ag=\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-fbsvc@megaz-78046.iam.gserviceaccount.com",
                "client_id" => "115299861553173115013",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fbsvc%40megaz-78046.iam.gserviceaccount.com",
                "universe_domain" => "googleapis.com"
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
