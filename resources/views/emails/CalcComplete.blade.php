@component('mail::message')
# Your calculations have completed.

Your calculations on Redshift Estimator have been completed. Click the button below or go to http://127.0.0.1:8000/history to view the results.

@component('mail::button', ['url' => 'http://127.0.0.1:8000/history'])
Take me there
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
