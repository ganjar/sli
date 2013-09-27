<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">

    <title>Пример работы SLI</title>

    <!-- Bootstrap core CSS -->
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function(){
           $('[data-toggle=tooltip]').tooltip({placement: 'bottom'});
        });
    </script>

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>

  <body style="padding-top: 80px;">

    <div class="navbar navbar-fixed-top navbar-default" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Название проекта</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Главная</a></li>
                <li><a href="#about">Про нас</a></li>
                <li><a href="#contact">Контакты</a></li>
            </ul>

            <!-- SLI: select language -->
            <?php SLIApi::ignoreStart();?>
            <ul class="nav navbar-nav navbar-right">
                <?php foreach(SLIApi::getLanguagesChangeList() as $val) {?>
                    <li<?php echo $val['selected'] ? ' class="selected"': '';?>>
                        <a % href="<?php echo $val['href'];?>" data-toggle="tooltip" title="<?php echo $val['title']?>">
                            <img src="/sli/static/img/flags/<?php echo $val['alias']?>.png" alt="<?php echo $val['title']?>">
                        </a>
                    </li>
                <?php }?>
            </ul>
            <?php SLIApi::ignoreEnd();?>
            <!-- SLI: end -->

        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </div><!-- /.navbar -->

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">
        <div class="col-xs-12 col-sm-9">
            <div>

                <h1>Рыба текст, встречайте!</h1><p>

                    С другой стороны рамки и место обучения кадров обеспечивает широкому кругу (специалистов) участие в формировании форм развития.
                    Таким образом начало повседневной работы по формированию позиции обеспечивает широкому кругу (специалистов) участие в формировании позиций, занимаемых участниками в отношении поставленных задач.
                    Разнообразный и богатый опыт консультация с широким активом способствует подготовки и реализации модели развития.   </p>
                <p>

                    Товарищи! постоянный количественный рост и сфера нашей активности обеспечивает широкому кругу (специалистов) участие в формировании соответствующий условий активизации.
                    С другой стороны дальнейшее развитие различных форм деятельности требуют от нас анализа направлений прогрессивного развития.
                    Повседневная практика показывает, что сложившаяся структура организации представляет собой интересный эксперимент проверки модели развития.
                    Повседневная практика показывает, что сложившаяся структура организации представляет собой интересный эксперимент проверки направлений прогрессивного развития.
                    Разнообразный и богатый опыт постоянный количественный рост и сфера нашей активности играет важную роль в формировании существенных финансовых и административных условий.   </p>
                <p>

                    С другой стороны новая модель организационной деятельности представляет собой интересный эксперимент проверки новых предложений.
                    Равным образом укрепление и развитие структуры позволяет оценить значение существенных финансовых и административных условий.
                    Повседневная практика показывает, что постоянное информационно-пропагандистское обеспечение нашей деятельности в значительной степени обуславливает создание позиций, занимаемых участниками в отношении поставленных задач.
                    Задача организации, в особенности же рамки и место обучения кадров обеспечивает широкому кругу (специалистов) участие в формировании позиций, занимаемых участниками в отношении поставленных задач.
                    Равным образом постоянное информационно-пропагандистское обеспечение нашей деятельности представляет собой интересный эксперимент проверки соответствующий условий активизации.   </p>
                <p>

                    Задача организации, в особенности же начало повседневной работы по формированию позиции играет важную роль в формировании соответствующий условий активизации.
                    Значимость этих проблем настолько очевидна, что рамки и место обучения кадров требуют определения и уточнения модели развития.
                    Повседневная практика показывает, что постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет выполнять важные задания по разработке модели развития.
                    Таким образом дальнейшее развитие различных форм деятельности представляет собой интересный эксперимент проверки модели развития.
                    Задача организации, в особенности же начало повседневной работы по формированию позиции влечет за собой процесс внедрения и модернизации систем массового участия.
                    Не следует, однако забывать, что дальнейшее развитие различных форм деятельности играет важную роль в формировании форм развития.   </p>
                <p>

                    Значимость этих проблем настолько очевидна, что новая модель организационной деятельности представляет собой интересный эксперимент проверки соответствующий условий активизации.
                    Повседневная практика показывает, что сложившаяся структура организации позволяет оценить значение систем массового участия.
                    Разнообразный и богатый опыт реализация намеченных плановых заданий представляет собой интересный эксперимент проверки систем массового участия.
                    Повседневная практика показывает, что консультация с широким активом представляет собой интересный эксперимент проверки систем массового участия.   </p>
                <p>

                    Идейные соображения высшего порядка, а также реализация намеченных плановых заданий требуют от нас анализа системы обучения кадров, соответствует насущным потребностям.
                    Не следует, однако забывать, что сложившаяся структура организации представляет собой интересный эксперимент проверки соответствующий условий активизации.
                    Значимость этих проблем настолько очевидна, что укрепление и развитие структуры позволяет выполнять важные задания по разработке форм развития.
                    Не следует, однако забывать, что укрепление и развитие структуры способствует подготовки и реализации новых предложений.   </p>
                <p>

                    Не следует, однако забывать, что рамки и место обучения кадров в значительной степени обуславливает создание форм развития.
                    Не следует, однако забывать, что реализация намеченных плановых заданий способствует подготовки и реализации модели развития.
                    Товарищи! консультация с широким активом в значительной степени обуславливает создание позиций, занимаемых участниками в отношении поставленных задач.   </p>
                <p>

                    С другой стороны постоянный количественный рост и сфера нашей активности способствует подготовки и реализации дальнейших направлений развития.
                    Равным образом новая модель организационной деятельности представляет собой интересный эксперимент проверки позиций, занимаемых участниками в отношении поставленных задач.
                    Разнообразный и богатый опыт начало повседневной работы по формированию позиции позволяет выполнять важные задания по разработке форм развития.
                    Идейные соображения высшего порядка, а также укрепление и развитие структуры играет важную роль в формировании позиций, занимаемых участниками в отношении поставленных задач.
                    Равным образом дальнейшее развитие различных форм деятельности позволяет выполнять важные задания по разработке системы обучения кадров, соответствует насущным потребностям.
                    Не следует, однако забывать, что новая модель организационной деятельности в значительной степени обуславливает создание системы обучения кадров, соответствует насущным потребностям.   </p>
                <p>

                    Повседневная практика показывает, что укрепление и развитие структуры влечет за собой процесс внедрения и модернизации дальнейших направлений развития.
                    Равным образом постоянный количественный рост и сфера нашей активности позволяет выполнять важные задания по разработке модели развития.
                    Товарищи! новая модель организационной деятельности требуют определения и уточнения системы обучения кадров, соответствует насущным потребностям.
                    Задача организации, в особенности же постоянный количественный рост и сфера нашей активности требуют от нас анализа позиций, занимаемых участниками в отношении поставленных задач.
                    Равным образом консультация с широким активом играет важную роль в формировании новых предложений.
                    Равным образом дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании новых предложений.   </p>
                <p>

                    Повседневная практика показывает, что дальнейшее развитие различных форм деятельности в значительной степени обуславливает создание соответствующий условий активизации.
                    Идейные соображения высшего порядка, а также рамки и место обучения кадров позволяет выполнять важные задания по разработке существенных финансовых и административных условий.
                    С другой стороны дальнейшее развитие различных форм деятельности позволяет выполнять важные задания по разработке существенных финансовых и административных условий.
                    С другой стороны дальнейшее развитие различных форм деятельности играет важную роль в формировании системы обучения кадров, соответствует насущным потребностям.   </p>
            </div>
        </div><!--/span-->

        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
          <div class="well sidebar-nav">
            <ul class="nav">
              <li>Комментарии</li>
              <li class="active"><a href="#">У нас 10 отзывов</a></li>
              <li><a href="#">У нас 20 отзывов</a></li>
              <li><a href="#">У нас 13 отзывов</a></li>
              <li>Навигация</li>
              <li><a href="#">Главное</a></li>
              <li><a href="#">Информация</a></li>
              <li><a href="#">Контакты</a></li>
              <li>Дополнительно</li>
              <li><a href="#">Уксус</a></li>
              <li><a href="#">Вода</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
      </div><!--/row-->

      <hr>

        <ul class="nav nav-list bd-catalog-filter-body affix-top">
            <li>
                <ul class="nav nav-pills history">
                    <li>
                        <a rel="nofollow" href="?s=filter&amp;history=1">
                            Просмотренные услуги (12)
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <div style="position: relative;" class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Киев<b class="caret"></b></a>
                    <ul class="dropdown-menu"><li><a rel="nofollow" href="?s=filter&amp;city=kiev">Киев</a></li><li><a rel="nofollow" href="?s=filter&amp;city=donetsk">Донецк</a></li><li><a rel="nofollow" href="?s=filter&amp;city=kharkov">Харьков</a></li><li><a rel="nofollow" href="?s=filter&amp;city=dnepropetrovsk">Днепропетровск</a></li><li><a rel="nofollow" href="?s=filter&amp;city=odessa">Одесса</a></li><li><a rel="nofollow" href="?s=filter&amp;city=lvov">Львов</a></li></ul> </div>
            </li>
            <li style="margin-top: 7px;">
                <div class="pills">
                    <ul class="nav nav-pills"><li class="ahover"><a rel="nofollow" href="?s=filter&amp;for_who=muzhchine">Мужчине</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;for_who=zhenschine">Женщине</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;for_who=dvoim">Двоим</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;for_who=detyam">Детям</a></li></ul> </div>
            </li>
            <li class="price" style="margin-top: 7px;">
                <div class="pills">
                    <div class="title">Цена (грн.):</div>
                    <ul class="nav nav-pills"><li class="ahover"><a rel="nofollow" href="?s=filter&amp;price=200">200</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;price=300">300</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;price=400">400</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;price=500">500</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;price=650">650</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;price=800">800</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;price=1000">1000</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;price=1200">1200</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;price=1600">1600</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;price=2000">2000</a></li><li class="ahover"><a rel="nofollow" href="?s=filter&amp;price=more">Дороже</a></li></ul> </div>
            </li>
            <li id="directions" class="directions" style="display: list-item;">
                <div class="title">
                    Знаешь что понравиться имениннику?
                </div>
                <div class="directions-list">
                    <ul>
                        <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="extreme"> <span>Экстрим</span>
                            </label>
                        </li>
                        <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="spa"> <span>Массажи и SPA</span>
                            </label>
                        </li>
                        <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="romantics"> <span>Романтика</span>
                            </label>
                        </li>
                    </ul><ul> <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="flies"> <span>Полеты</span>
                            </label>
                        </li>
                        <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="drive"> <span>Драйв</span>
                            </label>
                        </li>
                        <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="masterclasses"> <span>Мастер классы</span>
                            </label>
                        </li>
                    </ul><ul> <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="shooting"> <span>Стрельба</span>
                            </label>
                        </li>
                        <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="activity"> <span>Активный отдых</span>
                            </label>
                        </li>
                        <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="music"> <span>Музыка</span>
                            </label>
                        </li>
                    </ul><ul> <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="photo"> <span>Фотография</span>
                            </label>
                        </li>
                        <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="tvorchestvo"> <span>Творчество</span>
                            </label>
                        </li>
                        <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="sport"> <span>Спорт</span>
                            </label>
                        </li>
                    </ul><ul> <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="gurman"> <span>Гурман</span>
                            </label>
                        </li>
                        <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="ekskursii"> <span>Экскурсии</span>
                            </label>
                        </li>
                        <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="krasota"> <span>Красота</span>
                            </label>
                        </li>
                    </ul><ul> <li>
                            <label class="checkbox">
                                <input type="checkbox" id="direction" name="direction" value="neobychnoe"> <span>Необычное</span>
                            </label>
                        </li>
                    </ul>
                    <a class="btn btn-purple btn-submit" href="#">
                        <i class="icon-search icon-white"></i>
                        Искать впечатления
                    </a>
                </div>
                <div class="clearfix"></div>
            </li>
        </ul>
      <div class="row contacts">
          <div itemtype="http://schema.org/Organization" itemscope="" class="span5">
              <span style="display: none;" itemprop="name">Бодо</span>
              <h2>Контактные телефоны</h2>
              <div itemtype="http://schema.org/LocalBusiness" itemscope="" class="info-text">
                  <meta style="display: none;" content="UAH" itemprop="currenciesAccepted">
                  Звоните нам в любой день с
                  <time datetime="Пн-Вс 09:00-19:00" itemprop="openingHours">9:00 до 19:00</time>
                  <br>
                  на один из этих номеров:
              </div>
              <ul class="phones-list">
                  <li>
                      <span class="c-icon main"></span>
 <span itemprop="telephone" class="number">
 <span class="code">044</span> 593 33 22 </span>
                  </li>
                  <li>
                      <span class="c-icon mts"></span>
 <span class="number">
 <span class="code">050</span> 419 66 75 </span>
                  </li>
                  <li>
                      <span class="c-icon kyivstar"></span>
 <span class="number">
 <span class="code">067</span> 537 38 56 </span>
                  </li>
                  <li>
                      <span class="c-icon life"></span>
 <span class="number">
 <span class="code">093</span> 181 26 67 </span>
                  </li>
              </ul>
              <div class="mail">
                  <h3>Адрес для корреспонденции</h3>
                  <div class="c-icon i-mail"></div>
                  <div class="info">
 <span itemtype="http://schema.org/PostalAddress" itemscope="" itemprop="address">
 г. <span itemprop="addressLocality">Киев</span>,
 <span style="display: none;" itemprop="streetAddress">ул. Тверская 6, 4 этаж, офис 414</span>
 <span itemprop="postalCode">03150</span>,
 <span itemprop="streetAddress">а/я 230</span>.
 </span>
                      <br>
                      e-mail: <span itemprop="email">bodo@bodo.com.ua</span>
                  </div>
                  <div class="clearfix"></div>
              </div>
              <div class="representations">
                  <h3>Представительства</h3>
                  <div class="info-text">
                      <ul class="phones-list">
                          <li>
 <span class="city">
 <span class="title">Донецк</span>
 <span class="border"></span>
 </span>
 <span class="number">
 <span class="code">062</span> 210 54 52 </span>
                          </li>
                          <li>
 <span class="city">
 <span class="title">Одесса</span>
 <span class="border"></span>
 </span>
 <span class="number">
 <span class="code">048</span> 738 57 56 </span>
                          </li>
                          <li>
 <span class="city">
 <span class="title">Харьков</span>
 <span class="border"></span>
 </span>
 <span class="number">
 <span class="code">057</span> 728 52 13 </span>
                          </li>
                          <li>
 <span class="city">
 <span class="title">Львов</span>
 <span class="border"></span>
 </span>
 <span class="number">
 <span class="code">032</span> 242 49 50 </span>
                          </li>
                          <li>
 <span class="city">
 <span class="title">Днепропетровск</span>
 <span class="border"></span>
 </span>
 <span class="number">
 <span class="code">056</span> 790 85 84 </span>
                          </li>
                      </ul>
                  </div>
              </div>
              <div style="display: none;" class="logo-item">
                  <img src="/img/logo.png" itemprop="logo">
              </div>
          </div>
          <div class="span7">
              <h2>Всего 3 минуты от метро</h2>
              <div class="address">
                  Киев, м. Дворец Украина, ул. Тверская 6, 4 этаж, офис 414. </div>
              <div class="delivery-info">
                  Самовывоз и доставка работает 7 дней в неделю с 9:00 до 19:00 </div>
              <div style="height:300px;width: 100%;margin-top: 15px;" id="YMapsID-4751"><ymaps class="ymaps-map ymaps-i-ua_js_yes" style="z-index: 0; width: 670px; height: 300px;"><ymaps class="ymaps-glass-pane ymaps-events-pane" style="z-index: 500; position: absolute; width: 670px; height: 300px; left: 0px; top: 0px; -moz-user-select: none; cursor: url(&quot;http://api-maps.yandex.ru/2.0.33/debug/../images/ef50ac9e93aaebe3299791c79f277f8e.cur&quot;) 16 16, url(&quot;http://api-maps.yandex.ru/2.0.33/debug/../images/ef50ac9e93aaebe3299791c79f277f8e.cur&quot;), move;" unselectable="on"></ymaps><ymaps class="ymaps-layers-pane" style="z-index: 100; position: absolute; left: 335px; top: 150px;"><ymaps style="z-index: 150; position: absolute;"><ymaps style="position: absolute; left: 0px; top: 0px;"><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec02.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38322&amp;y=22159&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: -668px; top: -416px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec01.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38322&amp;y=22160&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: -668px; top: -160px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec02.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38322&amp;y=22161&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: -668px; top: 96px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec04.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38323&amp;y=22159&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: -412px; top: -416px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec03.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38323&amp;y=22160&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: -412px; top: -160px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec04.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38323&amp;y=22161&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: -412px; top: 96px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec02.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38324&amp;y=22159&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: -156px; top: -416px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec01.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38324&amp;y=22160&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: -156px; top: -160px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec02.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38324&amp;y=22161&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: -156px; top: 96px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec04.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38325&amp;y=22159&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: 100px; top: -416px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec03.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38325&amp;y=22160&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: 100px; top: -160px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec04.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38325&amp;y=22161&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: 100px; top: 96px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec02.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38326&amp;y=22159&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: 356px; top: -416px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec01.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38326&amp;y=22160&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: 356px; top: -160px;" unselectable="on"></ymaps><ymaps style="opacity: 1; background-size: 100% 100%; position: absolute; -moz-user-select: none; background-image: url(&quot;http://vec02.maps.yandex.net/tiles?l=map&amp;v=2.48.0&amp;x=38326&amp;y=22161&amp;z=16&amp;lang=ru_RU&quot;); width: 256px; height: 256px; left: 356px; top: 96px;" unselectable="on"></ymaps></ymaps></ymaps></ymaps><ymaps class="ymaps-copyrights-pane" style="z-index: 1000; position: absolute;"><ymaps><ymaps class="ymaps-copyrights-logo"><ymaps class="ymaps-logotype-div"><a class="ymaps-logo-link ymaps-logo-link-ru" target="_blank" href="http://maps.yandex.ru/?origin=jsapi&amp;ll=30.523361,50.419941&amp;z=16&amp;l=map"><ymaps class="ymaps-logo-link-wrap"></ymaps></a></ymaps></ymaps><ymaps class="ymaps-copyrights-legend"><ymaps class="ymaps-copyright-legend-container"><ymaps class="ymaps-copyright-legend"><ymaps class="ymaps-copyright-legend-element ymaps-copyright-legend-element-black"><ymaps style="display: inline;">&copy;&nbsp;Transnavicom, LTD</ymaps></ymaps></ymaps><ymaps class="ymaps-copyright-agreement ymaps-copyright-agreement-black"><ymaps class="ymaps-copyrights-ua-extended"><ymaps><a target="_blank" href="http://maps.yandex.ru/?ll=30.523361,50.419941&amp;z=16&amp;origin=jsapi&amp;fb">Сообщить об ошибке</a> · </ymaps></ymaps><a href="http://legal.yandex.ru/maps_termsofuse/" target="_blank">Пользовательское соглашение</a></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps class="ymaps-controls-pane" style="z-index: 800; position: static;"><ymaps class="ymaps-controls-righttop" style="z-index: 800;"><ymaps style="top: 5px; right: 5px; position: absolute;"><ymaps><ymaps class="ymaps-b-select ymaps-b-select_control_listbox"><ymaps role="button" class="ymaps-b-form-button ymaps-b-form-button_theme_grey-no-transparent-26 ymaps-b-form-button_height_26 ymaps-i-bem" unselectable="on" style="-moz-user-select: none;"><ymaps class="ymaps-b-form-button__left"></ymaps><ymaps class="ymaps-b-form-button__content"><ymaps class="ymaps-b-form-button__text"><ymaps id="id_137933101593364014_0" unselectable="on" style="-moz-user-select: none;"><ymaps><ymaps class="ymaps-b-select__title" style="display: block; width: 59px;">Схема</ymaps><ymaps title="Развернуть" class="ymaps-b-select__arrow"></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps class="ymaps-b-popupa ymaps-b-popupa_layout_yes ymaps-b-popupa_theme_white ymaps-i-bem"><ymaps class="ymaps-b-popupa__shadow"></ymaps><ymaps class="ymaps-b-popupa__body ymaps-b-popupa__body_theme_white"><ymaps class="ymaps-b-popupa__ie-gap">&nbsp;</ymaps><ymaps class="ymaps-b-listbox-panel" style=""><ymaps><ymaps class="ymaps-group"><ymaps><ymaps></ymaps><ymaps><ymaps class="ymaps-b-listbox-panel__item ymaps-b-listbox-panel__item_state_current"><ymaps class="ymaps-b-listbox-panel__item-link" unselectable="on" style="-moz-user-select: none;">Схема</ymaps><ymaps class="ymaps-b-listbox-panel__item-flag"></ymaps></ymaps></ymaps></ymaps><ymaps><ymaps></ymaps><ymaps><ymaps class="ymaps-b-listbox-panel__item "><ymaps class="ymaps-b-listbox-panel__item-link" unselectable="on" style="-moz-user-select: none;">Спутник</ymaps><ymaps class="ymaps-b-listbox-panel__item-flag"></ymaps></ymaps></ymaps></ymaps><ymaps><ymaps></ymaps><ymaps><ymaps class="ymaps-b-listbox-panel__item "><ymaps class="ymaps-b-listbox-panel__item-link" unselectable="on" style="-moz-user-select: none;">Гибрид</ymaps><ymaps class="ymaps-b-listbox-panel__item-flag"></ymaps></ymaps></ymaps></ymaps><ymaps><ymaps></ymaps><ymaps><ymaps class="ymaps-b-listbox-panel__item "><ymaps class="ymaps-b-listbox-panel__item-link" unselectable="on" style="-moz-user-select: none;">Народная карта</ymaps><ymaps class="ymaps-b-listbox-panel__item-flag"></ymaps></ymaps></ymaps></ymaps><ymaps><ymaps></ymaps><ymaps><ymaps class="ymaps-b-listbox-panel__item "><ymaps class="ymaps-b-listbox-panel__item-link" unselectable="on" style="-moz-user-select: none;">Народная + спутник</ymaps><ymaps class="ymaps-b-listbox-panel__item-flag"></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps class="ymaps-controls-lefttop" style="z-index: 800;"><ymaps style="top: 75px; left: 5px; position: absolute;" class="ymaps-b-zoom_hints-pos_right"><ymaps><ymaps class="ymaps-b-zoom"><ymaps class="ymaps-b-zoom__button ymaps-b-zoom__button_type_minus" unselectable="on" style="-moz-user-select: none;"><ymaps role="button" class="ymaps-b-form-button ymaps-b-form-button_size_sm ymaps-b-form-button_theme_grey-sm ymaps-b-form-button_height_26 ymaps-i-bem"><ymaps class="ymaps-b-form-button__left"></ymaps><ymaps class="ymaps-b-form-button__content"><ymaps class="ymaps-b-form-button__text"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps class="ymaps-b-zoom__scale" style="height: 125.667px; -moz-user-select: none;" unselectable="on"><ymaps class="ymaps-b-zoom__scale-bg"></ymaps><ymaps style="top: 115px;" class="ymaps-b-zoom__mark"><ymaps class="ymaps-b-zoom__mark-inner"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps></ymaps><ymaps class="ymaps-b-hint-placeholder"><ymaps><ymaps><ymaps class="ymaps-b-zoom__hint" style="top: 17px;"><ymaps class="ymaps-b-zoom__hint-left"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps><ymaps class="ymaps-b-zoom__hint-content"><ymaps class="ymaps-b-zoom__hint-text">мир</ymaps></ymaps><ymaps class="ymaps-b-zoom__hint-right"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps><ymaps><ymaps class="ymaps-b-zoom__hint" style="top: 38px;"><ymaps class="ymaps-b-zoom__hint-left"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps><ymaps class="ymaps-b-zoom__hint-content"><ymaps class="ymaps-b-zoom__hint-text">страна</ymaps></ymaps><ymaps class="ymaps-b-zoom__hint-right"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps><ymaps><ymaps class="ymaps-b-zoom__hint" style="top: 66px;"><ymaps class="ymaps-b-zoom__hint-left"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps><ymaps class="ymaps-b-zoom__hint-content"><ymaps class="ymaps-b-zoom__hint-text">город</ymaps></ymaps><ymaps class="ymaps-b-zoom__hint-right"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps><ymaps><ymaps class="ymaps-b-zoom__hint" style="top: 94px;"><ymaps class="ymaps-b-zoom__hint-left"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps><ymaps class="ymaps-b-zoom__hint-content"><ymaps class="ymaps-b-zoom__hint-text">улица</ymaps></ymaps><ymaps class="ymaps-b-zoom__hint-right"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps><ymaps><ymaps class="ymaps-b-zoom__hint" style="top: 115px;"><ymaps class="ymaps-b-zoom__hint-left"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps><ymaps class="ymaps-b-zoom__hint-content"><ymaps class="ymaps-b-zoom__hint-text">дом</ymaps></ymaps><ymaps class="ymaps-b-zoom__hint-right"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps class="ymaps-b-zoom__button ymaps-b-zoom__button_type_plus" unselectable="on" style="-moz-user-select: none;"><ymaps role="button" class="ymaps-b-form-button ymaps-b-form-button_size_sm ymaps-b-form-button_theme_grey-sm ymaps-b-form-button_height_26 ymaps-i-bem"><ymaps class="ymaps-b-form-button__left"></ymaps><ymaps class="ymaps-b-form-button__content"><ymaps class="ymaps-b-form-button__text"><ymaps class="ymaps-b-zoom__sprite"></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps style="top: 5px; left: 5px; position: absolute;"><ymaps class="ymaps-group"><ymaps><ymaps class="ymaps-group"><ymaps unselectable="on" style="-moz-user-select: none;"><ymaps><ymaps title="Переместить карту" class="ymaps-b-form-button ymaps-b-form-button_type_tool ymaps-b-form-button_valign_middle ymaps-b-form-button_theme_grey-no-transparent-26 ymaps-b-form-button_height_26 ymaps-i-bem ymaps-b-form-button_selected_yes"><ymaps class="ymaps-b-form-button__left"></ymaps><ymaps class="ymaps-b-form-button__content"><ymaps class="ymaps-b-form-button__text"><ymaps id="id_137933101593364014_1"><ymaps><ymaps class="ymaps-b-form-button__text"><ymaps class="ymaps-b-ico ymaps-b-ico_type_move"></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps unselectable="on" style="-moz-user-select: none;"><ymaps><ymaps title="Увеличить" class="ymaps-b-form-button ymaps-b-form-button_type_tool ymaps-b-form-button_valign_middle ymaps-b-form-button_theme_grey-no-transparent-26 ymaps-b-form-button_height_26 ymaps-i-bem"><ymaps class="ymaps-b-form-button__left"></ymaps><ymaps class="ymaps-b-form-button__content"><ymaps class="ymaps-b-form-button__text"><ymaps id="id_137933101593364014_2"><ymaps><ymaps class="ymaps-b-form-button__text"><ymaps class="ymaps-b-ico ymaps-b-ico_type_magnifier"></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps unselectable="on" style="-moz-user-select: none;"><ymaps><ymaps title="Измерение расстояний на карте" class="ymaps-b-form-button ymaps-b-form-button_type_tool ymaps-b-form-button_valign_middle ymaps-b-form-button_theme_grey-no-transparent-26 ymaps-b-form-button_height_26 ymaps-i-bem"><ymaps class="ymaps-b-form-button__left"></ymaps><ymaps class="ymaps-b-form-button__content"><ymaps class="ymaps-b-form-button__text"><ymaps id="id_137933101593364014_3"><ymaps><ymaps class="ymaps-b-form-button__text"><ymaps class="ymaps-b-ico ymaps-b-ico_type_ruler"></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps><ymaps class="ymaps-overlay-stepwise-pane" style="z-index: 600; position: absolute; left: 335px; top: 150px;"><ymaps class="ymaps-point-overlay" style="position: absolute; height: 0px; width: 0px; -moz-user-select: none; left: 0px; top: 0px; z-index: 650;" unselectable="on"><ymaps><ymaps class="ymaps-b-placemark ymaps-b-placemark_theme_blue" style="position: absolute; width: 59px; left: -42px; top: -41px;"><ymaps class="ymaps-b-placemark__inner"><ymaps class="ymaps-b-placemark__top"><ymaps class="ymaps-b-placemark__tl"><ymaps class="ymaps-b-placemark__sprite ymaps-b-placemark__sprite_pos_tl"></ymaps></ymaps><ymaps class="ymaps-b-placemark__tc"><ymaps class="ymaps-b-placemark__holster"><ymaps class="ymaps-b-placemark__sprite ymaps-b-placemark__sprite_pos_tc"></ymaps></ymaps></ymaps><ymaps class="ymaps-b-placemark__tr"><ymaps class="ymaps-b-placemark__sprite ymaps-b-placemark__sprite_pos_tr"></ymaps></ymaps></ymaps><ymaps class="ymaps-b-placemark__content"><ymaps id="id_137933101593364014_4" class="" style="height: 16px; display: block;"><ymaps>BODO</ymaps></ymaps></ymaps><ymaps class="ymaps-b-placemark__bottom"><ymaps class="ymaps-b-placemark__bl"><ymaps class="ymaps-b-placemark__sprite ymaps-b-placemark__sprite_pos_bl"></ymaps></ymaps><ymaps class="ymaps-b-placemark__bc"><ymaps class="ymaps-b-placemark__holster"><ymaps class="ymaps-b-placemark__sprite ymaps-b-placemark__sprite_pos_bc"></ymaps></ymaps></ymaps><ymaps class="ymaps-b-placemark__br"><ymaps class="ymaps-b-placemark__sprite ymaps-b-placemark__sprite_pos_br"></ymaps></ymaps><ymaps class="ymaps-b-placemark__bbr"><ymaps class="ymaps-b-placemark__sprite ymaps-b-placemark__sprite_pos_bbr"></ymaps></ymaps></ymaps><ymaps class="ymaps-b-placemark__left"><ymaps class="ymaps-b-placemark__sprite ymaps-b-placemark__sprite_pos_sl"></ymaps></ymaps><ymaps class="ymaps-b-placemark__right"><ymaps class="ymaps-b-placemark__sprite ymaps-b-placemark__sprite_pos_sr"></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></ymaps></div>
              <script type="text/javascript" src="http://api-maps.yandex.ru/2.0/?load=package.standard&amp;mode=debug&amp;lang=ru-RU"></script>
              <script type="text/javascript">
                  ymaps.ready(initYaMapContacts);
                  function initYaMapContacts () {
                      var myMap = new ymaps.Map("YMapsID-4751", {
                              center: [50.419941, 30.523361],
                              zoom: 16
                          }),
                          myPlacemark = new ymaps.Placemark([50.419941, 30.523361], {
                              iconContent: 'BODO',
                              balloonContentHeader: 'BODO',
                              balloonContentBody: "Интернет-магазин сертификатов на впечатления №1",
                              balloonContentFooter: ''
                          }, {
                              preset: 'twirl#blueStretchyIcon'
                          });
                      myMap.controls
                          .add('zoomControl')
                          .add('typeSelector')
                          .add('mapTools');
                      myMap.geoObjects.add(myPlacemark);
                  }
              </script>
              <div class="get-here">
                  <ul id="get-here" class="nav nav-tabs">
                      <li class="active"><a href="#afoot">Как добраться пешком</a></li>
                      <li><a href="#car">Как добраться на машине</a></li>
                  </ul>
                  <div class="tab-content">
                      <div id="afoot" class="tab-pane active">
                          От станции м. Дворец Украина нужно пройти один квартал в сторону м. Лыбедская (против движения автомобилей по ул. Красноармейская). На перекрестке возле кафе Кофе Хауз нужно повернуть налево и пройти еще один квартал, до пересечения ул. Тверская ул. Предславинская. На этом перекрестке со стороны ул. Тверская будет вход на ступеньках в здание. Возле входа самая большая вывеска &ndash; стоматология. Заходите внутрь, поднимаетесь на 4 этаж, офис 414 (возле дверей есть табличка Бодо). </div>
                      <div id="car" class="tab-pane">
                          Вход в наше здание находится на перекрестке ул. Тверская и ул. Предславинская. Здесь не всегда есть место для парковки. По этому лучше остановится на перекрестке ул. Красноармейская и ул. Тверская возле кафе Кофе Хауза. Дальше подняться вверх один квартал по ул. Тверская. В конце здания будет вход на ступеньках. Возле входа самая большая вывеска &ndash; стоматология. Заходите внутрь, поднимаетесь на 4 этаж, офис 414 (возле дверей есть табличка Бодо). </div>
                  </div>
                  <script type="text/javascript">
                      $('#get-here a').click(function (e) {
                          $(this).tab('show').blur();
                          e.preventDefault();
                      })
                  </script>
              </div>
          </div>
      </div>

      <footer>
        <p>© Компания 2013</p>
      </footer>

    </div><!--/.container-->

</body></html>