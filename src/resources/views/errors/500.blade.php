<script
  src="https://browser.sentry-cdn.com/6.9.0/bundle.min.js"
  integrity="sha384-WO22OE751vRf/HrLRHFis3ipNR16hUk5Q0qW9ascPaSswHI9Q/0ZFMaMvJ0ZgmSI"
  crossorigin="anonymous"
></script>

<div class="content">
    <div class="title">Something went wrong.</div>

    @if(app()->bound('sentry') && app('sentry')->getLastEventId())
      <div class="subtitle">Error ID: {{ app('sentry')->getLastEventId() }}</div>
      <script>
        Sentry.init({ dsn: 'https://e75029f28a974691925616a8d3c3a4a0@o923846.ingest.sentry.io/5871606' });
        Sentry.showReportDialog({
          eventId: '{{ app('sentry')->getLastEventId() }}',
          title: "Похоже, у нас проблемы.",
          subtitle: "Наша команда получила уведомление.",
          subtitle2: "Если вы хотите помочь, расскажите нам, что произошло.",
          labelName: "{{ __('auth.Name') }}",
          labelEmail: "{{ __('auth.E-Mail Address') }}",
          labelComments: "Что случилось?",
          labelClose: "{{ __('admin.close') }}",
          labelSubmit: "{{ __('admin.submit') }}",
          errorGeneric: "При отправке вашего отчета произошла неизвестная ошибка. Пожалуйста, попробуйте еще раз.",
          errorFormEntry: "Некоторые поля были заполнены некорректно. Пожалуйста, исправьте ошибки и попробуйте еще раз.",
          successMessage: "Ваш отзыв был отправлен. Спасибо!"
        });
      </script>
    @endif
</div>