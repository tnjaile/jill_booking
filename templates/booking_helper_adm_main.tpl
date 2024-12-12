
    <div class="row">
      <div class="col-xs-3 col-sm-2">
        <p>選擇預約場地：</p>
        <select name="jbi_sn" id="jbi-sn" size="10" class="form-control form-select"  title='jbi_sn'>
          <{foreach from=$items item=item}>
            <option value="<{$item.jbi_sn}>"><{$item.jbi_title}></option>
          <{/foreach}>
        </select>

        <div class="form-group" style="position: relative; margin-top: 10px;">
          <p>選擇預約日期：</p>
          <input id="booking-date" type="text" placeholder="選擇日期" class="form-control">
          <img id="date-img" onclick="WdatePicker({el:'booking-date', minDate:'%y-%M-%d'})" src="../images/datePicker.gif" style="position: absolute; bottom: 6px; right: 10px; cursor: pointer;">
        </div>

        <div class="form-group" style="margin-top: 10px;">
          <p>預約理由（選填）：</p>
          <input id="booking-event" type="text" placeholder="個人預約" class="form-control">
        </div>
      </div>

      <div class="col-xs-9 col-sm-10">
        <button type="button" class="btn btn-default btn-outline-primary" id="get-time-btn">取得時段</button>
        <button type="button" class="btn btn-default btn-outline-primary" id="order-btn" style="display: none;">預約</button>
        <div id="message" style="display: none; color: #ff0000; padding: 0 10px;"></div>
        <div id="jbi-date-text" style="margin: 10px 0;"></div>
        <div id="time-list"></div>
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

        const weekdays = ['日', '一', '二', '三', '四', '五', '六'];

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
                  msgBlock.text("預約完成！！");
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
            jbiDateText.text(`預約日期： ${data.jb_date} (${weekday})`);

            data.jbi_sn = +jbiSnSelect.val();
            //console.log(data, initData);

            timeList.text('資料載入中....');
            let dayOfWeek = new Date(data.jb_date).getDay();
            //console.log(data.jbi_sn, data.jb_date, dayOfWeek);
            $.get(`${location.href}?op=get_times_of_item&sn=${data.jbi_sn}&date=${data.jb_date}`)
             .then(data => {
                 let items = getTimeByDay(data, dayOfWeek);
                 getTimeBtn.hide();
                 (items && items.length > 0) ? generateTimeList(items) : timeList.text('無開放之時段');
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
