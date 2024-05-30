import domReady from '@roots/sage/client/dom-ready';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger.js';
import 'slick-carousel';
import $, { event } from 'jquery';
import axios from 'axios';

gsap.registerPlugin(ScrollTrigger);

/**
 * Application entrypoint
 */
domReady(async () => {

  // function change(val) { document.scriptform.action = `?month=${$val}&year=${$year}`; }

  const mainHeader = $('header.banner'),
      transHeader = mainHeader.hasClass('transparent');

  let lang = $('html').attr('lang');


    $(window).on('scroll', function() {
    let scrollTop = $(this).scrollTop();

    // Animazione header
    if ( scrollTop > mainHeader.height() ) {
        mainHeader.addClass('onscroll');
        if (transHeader == true)
        mainHeader.removeClass('transparent');
    } else {
        mainHeader.removeClass('onscroll');
        if (transHeader == true)
        mainHeader.addClass('transparent');
    }

    });

    // Mobile Main menu
    $('#ham').on('click', function() {
        let $this = $(this),
            $wrap = $('.mobile-nav nav');

        if ($this.hasClass('open')) {
            $this.removeClass('open');
            $wrap.removeClass('show');
            //$('html, body').css('overflow-y', 'auto');
        } else {
            $this.addClass('open');
            $wrap.addClass('show');
            //$('html, body').css('overflow-y', 'hidden');
        }
    });

    // Search form
    $('#icon-search').on('click', function() {
        let form = $('form#search');

        if (form.hasClass('hidden')) {
            form.removeClass('hidden');
            $(this).parent('.top-nav').addClass('hidden');
        }

    });

    $('form#search #close-form-search').on('click', function() {
        $(this).parent().addClass('hidden');
        $('.top-nav').removeClass('hidden');
    });

    $('.wpml-ls-item-toggle').on('click', function() {
        $(this).siblings('ul').toggleClass('open');
    });

    $('.mobile-nav nav li.menu-item-has-children').on('click', '> a', function(e) {
        e.preventDefault();

        let label = $(this).html(),
            link = $(this).attr('href'),
            parent_li = $(this).parent('li.menu-item-has-children'),
            submenu = parent_li.children('.main-submenu');

        $(this).addClass('parent-open');
        submenu.find('.parent-title').html(`<a href="${link}" style="padding: 0;">${label}</a>`);
        submenu.removeClass('closed');
        parent_li.siblings().addClass('level-under');
    });

    $('.back-to-parent').on('click', 'span', function(e) {
        e.preventDefault();

        let parent_ul = $(this).parents('ul.main-submenu'),
            parent_li = $(this).parents('li.menu-item-has-children');

        parent_li.removeClass('parent-open');
        parent_li.siblings().removeClass('level-under').delay(0.5);
        parent_ul.addClass('closed');
    });


  // Main slider in home
  // Se sono nella home
  if ($('body').hasClass('home')) {
    let hero_slides = gsap.utils.toArray(".bf-slider-home .single-slide"),
        nSlides = hero_slides.length;

        gsap.set(hero_slides, { xPercent: 100 });
        gsap.set(hero_slides[0], { xPercent: 0 });

        function BFSlider(i, prev) {
            gsap.fromTo(hero_slides[prev],	{xPercent: 0,zIndex: 0},{delay: 0.2,duration: 0.8,xPercent: 0,zIndex: -10});
            gsap.fromTo(hero_slides[i], { xPercent: 100, zIndex: 10, opacity: 1 }, { duration: 0.8, xPercent: 0, zIndex: 0, opacity: 1 });
            $(hero_slides[prev]).removeClass('current');
            $(hero_slides[i]).addClass('current');

            let prevImg = i != (nSlides -1) ? $(hero_slides[i+1]).attr('preview-img') : $(hero_slides[0]).attr('preview-img'),
                title = i != (nSlides -1) ? $(hero_slides[i+1]).find('.info .title').html() : $(hero_slides[0]).find('.info .title').html();

            $('.bf-slider-next-slide .ns-photo').css('background-image', `url(${prevImg})`);
            $('.bf-slider-next-slide .ns-title').html(title);
        }

        $('.bf-slider-controls li').on('click', function() {
            let number = parseInt($(this).attr('data-slide').replace('hero-', '')),
                currSlide = parseInt($('.bf-slider-home .single-slide.current').attr('id').replace('hero-', ''));

            if (!$(this).hasClass('active')) {
                $(this).addClass('active');
                $(this).siblings().removeClass('active');
            }
            BFSlider(number, currSlide);
        });

        $('.bf-slider-nav .span-nav').on('click', function() {
            let currSlide = parseInt($('.bf-slider-home .single-slide.current').attr('id').replace('hero-', ''));

            if ($(this).hasClass('prev')) {
                if (currSlide == 0) {
                    BFSlider(nSlides -1, currSlide);
                    $(`.bf-slider-controls li[data-slide=hero-${nSlides-1}]`).addClass('active');
                    $(`.bf-slider-controls li:not([data-slide=hero-${nSlides-1}])`).removeClass('active');
                } else {
                    BFSlider(currSlide -1, currSlide);
                    $(`.bf-slider-controls li[data-slide=hero-${currSlide-1}]`).addClass('active');
                    $(`.bf-slider-controls li:not([data-slide=hero-${currSlide-1}])`).removeClass('active');
                }
            } else {
                if (currSlide == nSlides-1) {
                    BFSlider(0, currSlide);
                    $(`.bf-slider-controls li[data-slide=hero-0]`).addClass('active');
                    $(`.bf-slider-controls li:not([data-slide=hero-0])`).removeClass('active');
                } else {
                    BFSlider(currSlide +1, currSlide);
                    $(`.bf-slider-controls li[data-slide=hero-${currSlide+1}]`).addClass('active');
                    $(`.bf-slider-controls li:not([data-slide=hero-${currSlide+1}])`).removeClass('active');
                }
            }
        });
    }

    $('.bf-carousel').slick({
        dots: true,
        customPaging : function(slider, i) {
            let thumb = $(slider.$slides[i]).data();
            // return '<a>'+(i+1)+'</a>'; // <-- old
            return '<button>'+('0'+(i+1)).slice(-2)+'</button>'; // <-- new
        },
        infinite: false,
        centerMode: false,
        variableWidth: false,
        slidesToShow: 1.2,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2500,
        cssEase: 'cubic-bezier(0.785, 0.135, 0.150, 0.860)',
        prevArrow: '<a class="prev"><i class="bf-icon icon-arrow-right white"></i></a>',
        nextArrow: '<a class="next"><i class="bf-icon icon-arrow-right white"></i></a>',
        responsive: [
        {
            breakpoint: 640,
            settings: {
                slidesToShow: 1.2,
                slidesToScroll: 1,
                arrows: false,
            },
        },
        ],
    });


    $('.casting.slide').slick({
        dots: true,
        customPaging : function(slider, i) {
            let thumb = $(slider.$slides[i]).data();
            // return '<a>'+(i+1)+'</a>'; // <-- old
            return `<a><span>${('0' + (i + 1)).slice(-2)}</span></a>`; // <-- new
        },
        arrows: false,
        infinite: false,
        centerMode: false,
        variableWidth: false,
        slidesToShow: 3.8,
        slidesToScroll: 3,
        autoplay: false,
        autoplaySpeed: 2500,
        cssEase: 'cubic-bezier(0.785, 0.135, 0.150, 0.860)',
        responsive: [
            {
                breakpoint: 1440,
                settings: {
                slidesToShow: 2.8,
                slidesToScroll: 2,
                },
            },
            {
                breakpoint: 1024,
                settings: {
                slidesToShow: 1.8,
                slidesToScroll: 1,
                },
            },
            {
                breakpoint: 768,
                settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                },
            },
        ],
    });

    if ($('.bf-parallax-image').length > 0) {

        let parImgs = gsap.utils.toArray(".bf-parallax-image");

        parImgs.forEach(parallax => {
            let imgMove = parallax.querySelector('.img-move'),
                row_parent = parallax.closest('.vc_row');

            gsap.set(imgMove, {xPercent: 85});
            gsap.to(imgMove, {
                ease: "none",
                yPercent: -120,
                scrollTrigger: {
                  trigger: row_parent,
                  // toggleActions: "restart none none reverse",
                  start: "top bottom",
                  markers: false,
                  scrub: 0.9,
                }
              });
        })

      }

    // Casting scripts
    $('#showMoreCast').on('click', function() {
        let parent = $(this).parents('.casting');

        if ($(this).hasClass('close')) {
            $(this).removeClass('close');
            parent.find('.cast.hidden').removeClass('hidden');
        } else {
            $(this).addClass('close');
            parent.find('.cast:not(.min)').addClass('hidden');
        }

    });

    $('.showBio').on('click', function() {
        let parent = $(this).parents('.el'),
            bio = parent.find('.bio'),
            textOpen = $(this).attr('data-open-text'),
            textClose = $(this).attr('data-close-text');

        if (bio.hasClass('hidden')) {
            bio.removeClass('hidden');
            $(this).html(textClose);
        } else {
            bio.addClass('hidden');
            $(this).html(textOpen);
        }

    });

    //accordion
    $('.casting.toggle').on('click', '.nome', function() {
        let parent = $(this).parents('.el');

        if (parent.hasClass('closed')) {
            parent.removeClass('closed').addClass('opened');
            parent.parent('.cast').siblings().find('.el').removeClass('opened').addClass('closed');
        } else {
            parent.removeClass('opened').addClass('closed');
        }

    });

    $('.menu-toggle').on('click', 'li.menu-item-has-children', function() {
        let li = $(this);

        if (li.hasClass('current-menu-parent')) {
            if ( li.hasClass('closed') ) {
                li.removeClass('closed').addClass('opened');
                li.siblings().removeClass('opened');
            } else {
                li.addClass('closed');
            }
        } else {
            if ( ! li.hasClass('opened')) {
                li.addClass('opened');
                li.removeClass('closed');
                li.siblings().removeClass('opened');
                li.siblings().addClass('closed');
            } else {
                li.removeClass('opened');
            }
        }

    });

    function showPsw() {
        let x = $("#user_pass");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

    $('.login-password').append('<i onclick="showPsw()" class="bf-icon icon-hide-password toggle-password"></i>');
    $('.login-password').on('click', 'i', function() {
        let x = $(this).parent().find("#user_pass");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    });

    if ($('body').hasClass('single-spettacoli')) {
        let primaDataLink = $('#date_evento option:nth-child(2)').val();

        if (primaDataLink != '') {
            $('.row-buy a.bf-btn').attr('href', primaDataLink);
        }

        /**
         * Collegameto alla pagina di acquisto biglietti
         * @see {@link partials/content-single-spettacoli.blade.php}
         */
        $('#buyTicket').on('click', function(e) {
            e.preventDefault();
            let select = $('select#date_evento').val();

            if (select != '') {
                window.location = select;
            }
        });
    }

    // Sezione Archivio Spettacoli
    if ($('body').hasClass('post-type-archive-spettacoli')) {

        let bodyclasses = $('body').attr('class').split(' '),
            terms = bodyclasses.filter(function(item) {
                return item.includes('term-');
            }),
            cat = $('body').is('[class*=term-]') ? terms[1].replace('term-', '') : 'all',
            loopEventi = `${AppData.site_url}/wp-json/wp/v2/spettacoli`,
            eventiCat = `${AppData.site_url}/wp-json/wp/v2/all-terms?term=categoria-spettacoli`,
            Eventi_by_date = `${AppData.site_url}/wp-json/wp/v2/events-datetime`,
            loopEvents = `${AppData.site_url}/wp-json/wp/v2/events_en`;

        const archEventi = new Vue({
            el: '#archivio',
            delimiters: ['${', '}'],
            props:['eventi', 'eventiCats'],
            data: () => ({
                eventi: [],
                eventiCats: [],
                order: 'asc',
                pages: '',
                currentCat: {
                    cat: '',
                    isActive: true
                },
                currentPage: {
                    id: '1',
                    isActive: true
                },
                pagination : [],
                queryArray: [],
                query: '',
                nEventi: 0,
                dateRequest: '',
                eventiDate: [],
            }),
            methods: {
                fetchSpettacoliCats() {
                    let ac = `${eventiCat}&lang=it`;
                    if (lang !== 'it-IT') {
                        ac = `${eventiCat}&lang=en`;
                    }

                    if (cat !== 'all') {
                        this.currentCat.cat = cat;
                    }

                    axios.get(ac)
                    .then(resp => {
                        this.eventiCats = resp.data;
                    })
                    .catch(err => {
                        // Manage the state of the application if the request
                        // has failed
                        console.log('error', err)
                    })
                },
                fetchSpettacoli() {
                    $('body').addClass('loading');

                    this.query = `${loopEventi}?orderby=data_inizio&order=${this.order}&per_page=12&page=${this.currentPage.id}`;

                    if (cat !== 'all') {
                        this.query = `${loopEventi}?categoria_spettacoli=${cat}&orderby=data_inizio&order=${this.order}&per_page=12&page=${this.currentPage.id}`;

                        if (lang !== 'it-IT') {
                            this.query = `${loopEvents}?categoria_spettacoli=${cat}&orderby=data_inizio&order=${this.order}&per_page=12&page=${this.currentPage.id}`;
                        }
                    } else {
                        if (lang !== 'it-IT') {
                            this.query = `${loopEvents}?orderby=data_inizio&order=${this.order}&per_page=12&page=${this.currentPage.id}`;
                        }
                    }
                    
                    axios.get(this.query)
                    .then(resp => {
                        this.pages = resp.headers.get('x-wp-totalpages');
                        this.eventi = resp.data;
                        this.nEventi = this.eventi.length;

                        if (this.eventi.length == 0) {
                            let msg = lang !== 'it-IT' ? 'Sorry, no shows found' : 'Spiacenti non sono presenti spettacoli';
                            $('.ev-container').html(`<p>${msg}</p>`);
                        } else {
                            this.createPage(this.pages);
                            $('.ev-container p').remove();
                        }

                        window.scrollTo(0, 0);
                        $('body').removeClass('loading');
                    })
                    .catch(err => {
                        // Manage the state of the application if the request
                        // has failed
                        console.log('error', err)
                        $('body').removeClass('loading');
                    })
                },
                getDate(e) {
                    let data_arr = (e.target.value).split('/'),
                        giorno = parseInt(data_arr[2]),
                        data = `${data_arr[0]}/${data_arr[1]}/${data_arr[2]}`;

                    // Controllo se il giorno scelto ha solo una cifra
                    // e aggiungo uno zero davanti
                    if (giorno < 10) data = `${data_arr[0]}/${data_arr[1]}/0${giorno}`;

                    this.dateRequest = data;
                    console.log(data);
                    this.avviaFiltri ('data', data);
                },
                onClickPage(page) {
                    this.currentPage.id = page;
                    this.avviaFiltri ('page', '', page);
                },
                createPage() {
                    let array = [];
                    for (let i = 0; i < this.pages; i++) {
                        array.push({id: i + 1,})
                    }
                    this.pagination = array;
                },
                async changeCat(cat_id) {
                    // Prendo lo slug della categoria
                    let slug = '',
                        tax_slug = lang !== 'it-IT' ? 'en/events-category' : 'categoria-spettacoli',
                        eventiPage = lang !== 'it-IT' ? 'en/events' : 'spettacoli';

                    if (cat_id !== 'all') {
                        
                        // Prendo lo slug della categoria
                        this.eventiCats.map(function(elem){
                            if (elem.term_id == cat_id) {
                                slug = elem.slug;
                            }
                        });

                        if (slug !== '') {
                            window.location = `${AppData.site_url}/${tax_slug}/${slug}`;
                        }
                    } else {
                        window.location = `${AppData.site_url}/${eventiPage}`;
                    }
                },
                async avviaFiltri (type, id, page) {
                    this.queryArray = [];

                    // Non è attivo il pagination
                    if (!page) {
                        if (type != 'cat')
                            page = this.currentPage.id;
                        else
                            page = '1';
                    }

                    // Se sto filtrando per categoria
                    if (type == 'cat') {

                        if (id != 'all') {
                            if (this.eventiCats.length > 0) {
                                this.queryArray.push(`categoria_spettacoli=${id}`)
                            }
                        }

                        this.queryArray.push(`orderby=data_inizio&order=${this.order}&per_page=12&page=${page}`)

                        let queryString = this.queryArray.join('&');

                        if (lang !== 'it-IT') {
                            this.query = `${loopEvents}?${queryString}`;
                        } else {
                            this.query = `${loopEventi}?${queryString}&acf_format=standard`;
                        }
                    }

                    // Se sto filtrando per data
                    if (type == 'data') {
                        this.query = Eventi_by_date;
                    }

                    // Se sto usando il pagination
                    if (type == 'page') {

                        if (this.currentCat.cat !== '' && this.currentCat.cat !== 'all') {
                            this.queryArray.push(`categoria_spettacoli=${this.currentCat.cat}`);
                        }
                        
                        this.queryArray.push(`orderby=data_inizio&order=${this.order}&per_page=12&page=${page}`)

                        let queryString = this.queryArray.join('&');

                        if (lang !== 'it-IT') {
                            this.query = `${loopEvents}?${queryString}&acf_format=standard`;
                        } else {
                            this.query = `${loopEventi}?${queryString}&acf_format=standard`;
                        }
                    }

                    $('body').addClass('loading');
                    await axios.get(this.query)
                    .then(resp => {

                        if (type == 'cat') {
                            this.pages = resp.headers.get('x-wp-totalpages');
                            this.createPage(this.pages);
                            this.eventi = resp.data;
                            this.currentCat.cat = id;
                            $('.ev-container p').remove();
                            window.scrollTo(0, 0); 
                            $('body').removeClass('loading');
                        }
                        else if (type == 'data') {
                            let risposta = resp.data.date;
                            let arrayIds = [];

                            for (const [key, value] of Object.entries(risposta)) {
                                if ( key == id) {
                                    arrayIds = Object.keys(value).map((key) => [key, value[key]]);
                                }
                            }

                            let ids = arrayIds.join(',')

                            axios.get(`${loopEventi}?id=${ids}`)
                            .then(resp => {
                                this.pages = resp.headers.get('x-wp-totalpages');
                                this.createPage(this.pages);
                                this.eventi = resp.data;
                                this.nEventi = this.eventi.length;

                                if (this.eventi.length == 0) {
                                    let msg = lang !== 'it-IT' ? 'Sorry, no shows found on this date' : 'Spiacenti non sono presenti spettacoli per questa data';
                                    $('.ev-container').html(`<p>${msg}</p>`);
                                }

                                $('body').removeClass('loading');
                                $('li.cat').removeClass('open');
                                $('li.cat ul').removeClass('show');
                                $('.bf-calendar-choice #close-cal i').trigger('click');
                            })
                            .catch(err => {
                                // Manage the state of the application if the request
                                // has failed
                                console.log('error', err)
                                $('body').removeClass('loading');
                            })
                        } else {
                            this.eventi = resp.data;
                            window.scrollTo(0, 0); 
                            $('body').removeClass('loading');
                            $('.bf-calendar-choice #close-cal i').trigger('click');
                        }

                        this.nEventi = this.eventi.length;
                        $('body').removeClass('loading');
                        $('li.cat').removeClass('open');
                        $('li.cat ul').removeClass('show');
                    })
                    .catch(err => {
                        // Manage the state of the application if the request
                        // has failed
                        console.log('error', err)
                        $('body').removeClass('loading');
                    })

                },
            },
            mounted: function() {
                this.fetchSpettacoli();
                this.fetchSpettacoliCats();
            }
        });
    }

    //Sezione form rimborsi
    if ($('body').hasClass('page-template-rimborsi')) {
        const formRimborsi = new Vue({
            el: '#rimborso',
            delimiters: ['${', '}'],
            data: () => ({
                shows: [],
                show_id: '',
                date: [],
                tickets: [{
                    qty: 'qty-0',
                    sector:'sector-0'
                }],
                files: [
                    {
                        id:'file-0',
                        class: 'file-0'
                    },
                    {
                        id:'file-1',
                        class: 'file-1 hidden'
                    },{
                        id:'file-2',
                        class: 'file-2 hidden'
                    },
                    {
                        id:'file-3',
                        class: 'file-3 hidden'
                    },
                    {
                        id:'file-4',
                        class: 'file-4 hidden'
                    }

                ],
                indexFileShow: 0,
            }),
            methods: {
                fetchSpettacoliAnnullati() {
                    axios.get(`${AppData.site_url}/wp-json/wp/v2/spettacoli?annullato=true&per_page=100`)
                    .then(resp => {
                        this.shows = resp.data;
                        console.log(this.shows)
                    })
                    .catch(err => {
                        // Manage the state of the application if the request
                        // has failed
                        console.log('error', err)
                    })
                },
                fetchShowId() {
                    let select = document.querySelector(`#show-name`).value;
                    let option = document.querySelector(`option[value="${select}"]`).getAttribute('id');
                    console.log(option);
                    this.show_id = option;
                    this.fetchDate(option);
                },
                fetchDate(id) {
                    axios.get(`${AppData.site_url}/wp-json/wp/v2/spettacoli?id=${id}`)
                    .then(resp => {
                        // console.log(resp.data[0].acf.date_annullate);
                        this.date = resp.data[0].acf.date_annullate;
                        console.log(this.date)
                    })
                    .catch(err => {
                        // Manage the state of the application if the request
                        // has failed
                        console.log('error', err)
                    })
                },
                addTicket() {
                    let tikount = this.tickets.length;
                    this.tickets.push({qty: `qty-${tikount}`, sector:`sector-${tikount}`});
                },
                removeTicket() {
                    this.tickets.splice(-1);
                },
                addFile() {
                    // let i = parseInt(this.indexFileShow);
                    if (this.indexFileShow < 5) {
                        let filecount = this.indexFileShow +1;
                        $(`label.file-${filecount}`).removeClass('hidden');

                        this.indexFileShow = filecount;
                    }
                    console.log(this.indexFileShow)
                },
                removeFile() {
                    // let i = parseInt(this.indexFileShow);
                    if (this.indexFileShow != 0) {
                        let filecount = this.indexFileShow -1;
                        $(`label.file-${filecount}`).addClass('hidden');

                        this.indexFileShow = filecount;
                    }
                    console.log(this.indexFileShow)
                }
            },
            mounted: function() {
                this.fetchSpettacoliAnnullati();
            }
        });
    }

    //Sezione form prenotazione spettacoli scuole
    if ($('body').hasClass('page-template-educational')) {
        const formRimborsi = new Vue({
            el: '#prenotazione_scuole',
            delimiters: ['${', '}'],
            data: () => ({
                shows: [],
                show_id: '',
                date_1: [],
                date_2: [],
                files: [
                    {
                        id:'file-0',
                        class: 'file-0'
                    },
                    {
                        id:'file-1',
                        class: 'file-1 hidden'
                    },{
                        id:'file-2',
                        class: 'file-2 hidden'
                    },
                    {
                        id:'file-3',
                        class: 'file-3 hidden'
                    },
                    {
                        id:'file-4',
                        class: 'file-4 hidden'
                    }

                ],
                indexFileShow: 0,
                fattura: 'no'
            }),
            methods: {
                fetchSpettacoli() {
                    axios.get(`${AppData.site_url}/wp-json/wp/v2/vivaticket-events`)
                    .then(resp => {
                        this.shows = resp.data;
                        // console.log(this.shows)
                    })
                    .catch(err => {
                        // Manage the state of the application if the request
                        // has failed
                        console.log('error', err)
                    })
                },
                fetchShowId(id) {
                    let option = $(`#show-name${id} option:selected`).attr('id');
                    console.log(option);
                    // this.show_id = option;
                    this.fetchDate(id, option);
                },
                fetchDate(n, id) {
                    let date = [];

                    this.shows.map(function(elem){
                        if (elem.ID == id) {
                            for (const [key, value] of Object.entries(elem.date)) {
                                date.push(value)
                            }
                        }
                    });
                    
                    switch (n) {
                        case 1:
                            this.date_1 = date;
                            console.log(this.date_1)
                            break;
                    
                        case 2:
                            this.date_2 = date;
                            console.log(this.date_2)
                            break;
                        default:
                            break;
                    }
                    
                },
                addFile() {
                    // let i = parseInt(this.indexFileShow);
                    if (this.indexFileShow < 5) {
                        let filecount = this.indexFileShow +1;
                        $(`label.file-${filecount}`).removeClass('hidden');

                        this.indexFileShow = filecount;
                    }
                    console.log(this.indexFileShow)
                },
                removeFile() {
                    // let i = parseInt(this.indexFileShow);
                    if (this.indexFileShow != 0) {
                        let filecount = this.indexFileShow -1;
                        $(`label.file-${filecount}`).addClass('hidden');

                        this.indexFileShow = filecount;
                    }
                    console.log(this.indexFileShow)
                },
                changeFattura() {
                    this.fattura = $('#fattura option:selected').val();
                }
            },
            mounted: function() {
                this.fetchSpettacoli();
            }
        });
    }

    // Upload file rimborsi
    $('input[type="file"]').on('change', function(e) {
        console.log('file changing')
        let fd = new FormData(),
            id = $(this).attr('id'),
            files = $(this)[0].files[0],
            fileExtension = $(this).attr('data-mime_types').split(','),
            fileMaxSize = $(this).attr('max-size');

            console.log(id);

            fd.append('action', 'upload_file');
            fd.append('upload_file', 'upload_nonce');
            fd.append('inputID', id);
            fd.append('tipo', 'rimborsi');

        fd.append('file', files);
        console.log(files['size'] <= fileMaxSize);

        let filetypes = files['type'].indexOf('/') >= 0 ? [files['type'].split('/').pop()] : [files['type']];

        if ($(this).attr('data-mime_types') != '' || files['size'] > fileMaxSize) {
          if (fileExtension.indexOf(filetypes[0]) < 0) {
                if (lang == 'it-IT')
                    alert(`Tipo di file non valido, caricare un file in formato ${fileExtension.join(' o ')}, grazie.`);
                else
                    alert(`File type not allowed, please upload a file with format ${fileExtension.join(' or ')}, thank you.`);

              $(this).val('');
          } else if (files['size'] > fileMaxSize) {

            if (lang == 'it-IT')
                alert(`Questo file eccede le dimensioni massime consentite`);
            else
                alert(`This file exceeds the maximum allowed file size`);

            $(this).val('');
          } else {
            uploadData(fd);
          }
        } else {
          uploadData(fd);
        }
    });

    // Sending AJAX request and upload file
    function uploadData(formdata) {
        $.ajax({
            url: AppData.ajax_url,
            type: 'post',
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                console.log(response);

                let id = response.data.id;

                $(`input.${id}`).val(response.data.src);
                $(`input.${id}`).attr('remove', response.data.path);
            },
            error: function(xhr, resp, text) {
                console.log('errore upload file');
            }

        });
    }

    $('#rimborso').on('submit', function(e) {
        e.preventDefault();
        let formdata = $(this).serializeArray(),
            hidden_files = $('*input[name^="file"]');

            for (let index = 0; index < hidden_files.length; index++) {
                let input = hidden_files[index];
                if (input.getAttribute('remove') !== '') {
                    formdata.push({name: 'fileRemove', value: input.getAttribute('remove')});
                }
            }

        $.ajax({
            type: 'post',
            url: AppData.ajax_url,
            data: {
              action: 'invia_mail_rimborso',
              formData: formdata,
            },
            success: function(response) {
                console.log(response.data);
                let message = $('input#refund-message-ok').val(), //response.data.message_ok,
                    file_remove = response.data.uploads;

                alert(message);
                $('#rimborso').find('input, textarea, select').val('');
            },
            error: function(error) {
                console.log(error)

                let message = $('input#refund-message-ko').val();
                alert(message)
            }
        });
    });

    $('#prenotazione_scuole').on('submit', function(e) {
        e.preventDefault();
        let formdata = $(this).serializeArray(),
            hidden_files = $('*input[name^="file"]');

            for (let index = 0; index < hidden_files.length; index++) {
                let input = hidden_files[index];
                if (input.getAttribute('remove') !== '') {
                    formdata.push({name: 'fileRemove', value: input.getAttribute('remove')});
                }
            }

        console.log(formdata);

        $.ajax({
            type: 'post',
            url: AppData.ajax_url,
            data: {
              action: 'invia_mail_prenotazione',
              formData: formdata,
            },
            success: function(response) {
                console.log(response.data);
                let message = response.data.message_ok,
                    file_remove = response.data.uploads;

                alert(message);

                $.ajax({
                    type: 'post',
                    url: AppData.ajax_url,
                    data: {
                      action: 'elimina_allegati_rimborsi',
                      uploads: file_remove,
                    },
                    success: function(data) {
                        console.log('file eliminati');
                        $('#prenotazione_scuole').find('input, textarea, select').val('');
                    },
                    error: function(error) {
                      console.log(error)
                    }
                });
            },
            error: function(error) {
                console.log(error)
            }
        });
    });


    // Calendar choice
    $('#filtri li span').on('click', function() {
        let parent = $(this).parent();
        let cal = $('.bf-calendar-choice');

        if (parent.hasClass('open')) {
            parent.removeClass('open')
        } else {
            parent.addClass('open')
        }

        if (parent.hasClass('calendar')) {
            if (cal.hasClass('view')) {
                cal.removeClass('view');
            } else {
                cal.addClass('view');
            }
        } else {
            if (parent.find('ul').hasClass('show')) {
                parent.find('ul').removeClass('show');
            } else {
                parent.find('ul').addClass('show');
            }
        }
    });

    $('.bf-calendar-choice #close-cal').on('click', 'i', function() {
        let cal = $('.bf-calendar-choice');
        cal.removeClass('view');

        $('#filtri li.calendar').removeClass('open');
    });

    if ($('body').hasClass('woocommerce-orders') ) {
        let buttonOrderView = jQuery( 'a.button.view' ).toArray();

        for (let index = 0; index < buttonOrderView.length; index++) {
            const element = buttonOrderView[index];
            let viewOrderUrl = $(element).attr('data-url');
            $(element).attr('href', viewOrderUrl);
        }
    }

    /**
     * Fermo lo scroll quando si apre il carrello
     */
    (function($) {
        $(document).ajaxComplete(function() {
            if ($('body').hasClass('woocommerce-checkout') || $('body').hasClass('woocommerce-cart')) {
                jQuery( 'html, body' ).stop();
            }
        });
    })(jQuery);

});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);