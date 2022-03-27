<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2">
      <p><{$smarty.const._MA_JILLBOOKIN_SELECT_APPOINTMENT_VENUE}></p>
      <select name="jbi_sn" id="jbi-sn" size="10" style="width: 100%;"  title='jbi_sn'>
        <{foreach from=$items item=item}>
          <option value="<{$item.jbi_sn}>"><{$item.jbi_title}></option>
        <{/foreach}>
      </select>

      <div class="form-group" style="position: relative; margin-top: 10px;">
        <p><{$smarty.const._MA_JILLBOOKIN_SELECT_APPOINTMENT_DATE}></p>
        <input id="booking-date" type="text" placeholder="<{$smarty.const._MA_JILLBOOKIN_SELECT_DATE}>" class="form-control">
        <img id="date-img" onclick="WdatePicker({el:'booking-date', minDate:'%y-%M-%d'})" src="../images/datePicker.gif" style="position: absolute; bottom: 6px; right: 10px; cursor: pointer;">
      </div>

      <div class="form-group" style="margin-top: 10px;">
        <p><{$smarty.const._MA_JILLBOOKIN_BOOKING_REASON_OPTIONAL}></p>
        <input id="booking-event" type="text" placeholder="<{$smarty.const._MA_JILLBOOKIN_INDIVIDUAL_BOOKING}>" class="form-control">
      </div>
    </div>

    <div class="col-sm-9 col-md-10">
      <button type="button" class="btn btn-default btn-outline-primary" id="get-time-btn"><{$smarty.const._MA_JILLBOOKIN_GET_TIME}></button>
      <button type="button" class="btn btn-default btn-outline-primary" id="order-btn" style="display: none;"><{$smarty.const._MA_JILLBOOKIN_RESERVE}></button>
      <div id="message" style="display: none; color: #ff0000; padding: 0 10px;"></div>
      <div id="jbi-date-text" style="margin: 10px 0;"></div>
      <div id="time-list"></div>
    </div>
  </div>
</div>

<script>
    ;(function($) {
        let jbiSnSelect = $('#jbi-sn'),
          bookingDateInput = $('#booking-date'),
          jbiDateText = $('#jbi-date-text'),
          timeList = $('#time-list'),
          dateImg = $('#date-img'),
          eventInput = $('#booking-event'),
          getTimeBtn = $('#get-time-btn'),
          orderBtn = $('#order-btn'),
          msgBlock = $('#message');

        const initData = {
            'jb_date': null,
            'jbi_sn' : null
        };

        const weekdays = [<{$smarty.const._MA_JILLBOOKIN_WEEKDAY_ARRAY}>];

        let data = {...initData};
        // console.log(data, initData);

        bookingDateInput.on('blur', init);
        dateImg.on('click', init);
        jbiSnSelect.on('change', init);

        getTimeBtn.on('click', getTimesOfItem);

        orderBtn.on('click', () => {
          let values = [];
          $('input[type=checkbox]:checked').each((_, item) => values.push(+item.value));

          if (values.length === 0) {
              return;
          }

          $.ajax({
              type: 'POST',
              url: `${location.href}?op=create_orders`,
              async: true,
              data: JSON.stringify({...data, event: eventInput.val(), values}),
              contentType: "application/json; charset=utf-8",
              dataType: "json",
              success: data => {
                  init();
                  getTimeBtn.hide();
                  // bookingDateInput.val(null);
                  msgBlock.text("<{$smarty.const._MA_JILLBOOKIN_APPOINTMENT_COMPLETE}>");
                  msgBlock.show();
                  setTimeout(() => {
                      msgBlock.text(null);
                      msgBlock.hide();
                      getTimeBtn.show();
                  }, 2000);
              }
          });

        });


        init();

        // 初始化
        function init() {
            // jbiSnSelect.val(null);
            jbiDateText.text(null);
            timeList.text(null);
            // eventInput.val(null);
            // bookingDateInput.val(null);
            // msgBlock.hide();
            getTimeBtn.show();
            orderBtn.hide();
            // jbiSnSelect.prop('disabled', bookingDateInput.val() === '');
            data = {...initData};
            // console.log(data, initData);
        }

        // 取得某場地的全部時段
        function getTimesOfItem() {

            if (bookingDateInput.val() === '' || jbiSnSelect.val() === '') {
                return;
            }

            data.jb_date = bookingDateInput.val();

            let weekday = weekdays[(new Date(data.jb_date)).getDay()];
            jbiDateText.text(`<{$smarty.const._MA_JILLBOOKIN_APPOINTMENT_DATE}> ${data.jb_date} (${weekday})`);

            data.jbi_sn = +jbiSnSelect.val();
            //console.log(data, initData);

            timeList.text('<{$smarty.const._MA_JILLBOOKIN_LOADING_DATA}>');
            let dayOfWeek = new Date(data.jb_date).getDay();
            //console.log(data.jbi_sn, data.jb_date, dayOfWeek);
            $.get(`${location.href}?op=get_times_of_item&sn=${data.jbi_sn}&date=${data.jb_date}`)
             .then(data => {
                 let items = getTimeByDay(data, dayOfWeek);
                 getTimeBtn.hide();
                 (items && items.length > 0) ? generateTimeList(items) : timeList.text('<{$smarty.const._MA_JILLBOOKIN_NO_OPENING_HOURS}>');
             }).catch(err => console.log(err));
        }

        // 取得當天開放之時段
        function getTimeByDay(data, day) {
          if (!data) {
              return null;
          }
          return data.filter(item => {
            // 若某時段一週只有1天開放，則其 jbt_week 為數字，無法使用 indexOf
            // 因此先與空字串串接，強制轉為字串
            return (item.jbt_week + '').indexOf(day) > -1;
          });
        }

        // 產生時段列表
        function generateTimeList(items) {
            timeList.text('');
            items.forEach(item => {
              timeList.append(`
                <div class="checkbox ${item.can_order ? '' : 'cant-use'}" style="margin-bottom: 15px;">
                  <label>
                    <input type="checkbox" value="${item.jbt_sn}" ${item.can_order ? '' : 'disabled'}>
                    <span class="${item.can_order ? '' : 'cant-use'}">${item.jbt_title}</span>
                    ${item.can_order ? '' : 'by ' + item.order_user + ' (' + item.event + ')'}
                  </label>
                </div>
              `);
            });
            orderBtn.show();
        }
    })($);
</script>
