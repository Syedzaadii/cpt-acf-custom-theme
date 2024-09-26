<ul class="professor-cards">

    <li class="professor-card__list-item">
        <a href="<?php the_permalink(); ?>" class="professor-card">

            <img src="<?php the_post_thumbnail_url(); ?>" alt="" class="professor-card__image">
            <span class="professor-card__name"><?php the_title(); ?></span>
        </a>
    </li>
</ul>