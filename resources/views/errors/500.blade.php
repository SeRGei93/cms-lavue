<!DOCTYPE html>
<html>
    <head>
        <title>{{ trans('global.error_page_title') }}</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
            .error_page_title {
                font-size: 40px;
                margin-bottom: 40px;
            }

            .btn-back {
                border: 1px solid;
                border-radius: 4px;
                text-decoration: none;
                padding: 5px 10px;
                margin-top: 50px;
                color: #B0BEC5;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">{{ trans('global.error_page_title') }}</div>
                <div class="error_page_title">{{ trans('global.error_page_content') }}</div>
                <a href="javascript:history.back()" class="btn-back">&#8592; {{ trans('global.back') }}</a>
                @if(app()->bound('sentry') && !empty(Sentry::getLastEventID()))
                    {{--<div class="subtitle">Error ID: {{ Sentry::getLastEventID() }}</div>--}}

                    <!-- Sentry JS SDK 2.1.+ required -->
                    <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

                    <script>
                        Raven.showReportDialog({
                            eventId: '{{ Sentry::getLastEventID() }}',
                            // use the public DSN (dont include your secret!)
                            dsn: 'https://fb44778035ad492087741e69a43ca658:d98071c25f98494aa21b9bc5fa09218b@sentry.io/260408',
                            user: {
                                'name': 'Stanislav',
                                'email': 'stasskrim_-92@mail.ru',
                            }
                        });
                    </script>
                @endif
            </div>
        </div>
    </body>
</html>
