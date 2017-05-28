<?php get_header(); ?>

<div class="container">

    <?php if ( have_posts() ) :  while ( have_posts() ) : the_post(); ?>

        <h1><?php the_title();?></h1>
        <p><?php the_content();?></p>

    <?php endwhile; ?>
    <?php endif; ?>

</div>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
    ymaps.ready(init);
    var myMap, 
        myPlacemark;

    function init(){ 
        myMap = new ymaps.Map("map", {
            center: [47.819188, 35.200806],
            zoom: 16
        }); 
        
        myPlacemark = new ymaps.Placemark([47.819188, 35.200806], {
            hintContent: 'Дом Канцелярии',
            balloonContent: 'Гипер-Маркет Канцелярии'
        });
        
        myMap.geoObjects.add(myPlacemark);
    }
</script>

<?php get_footer(); ?>