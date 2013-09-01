plasma
======
```
##### #     ##### ##### ##### #####
#   # #     #   # #     # # # #   #
##### #     ##### ##### # # # #####
#     #     #   #     # #   # #   #
#     ##### #   # ##### #   # #   #
```

dev-Beta-2 : intègre les modifications liées à l'utilisation de la timeline dans le backoffice


fonction de tri des écrans en javascript (http://jsfiddle.net/WDzXc/) :
```javascript
var slides = [
    {ref_target:'ecr',start:123,order:0},
    {ref_target:'ord',start:0,order:2},
    {ref_target:'grp',start:56,order:0},
    {ref_target:'ord',start:0,order:1},
    {ref_target:'ecr',start:98,order:0},
    {ref_target:'loc',start:23,order:0},
    {ref_target:'nat',start:9,order:0},
    {ref_target:'nat',start:10,order:0}
];


slides = slides.sort(function(a,b){
    $slides = $slides.sort(function(a,b){
    valeur =
    (a.ref_target == 'loc' && b.ref_target == 'nat' ? -1 :
     (a.ref_target == 'nat' && b.ref_target == 'loc' ? 1 :
    
      (a.ref_target == 'nat' && b.ref_target == 'grp' ? -1 :
       (a.ref_target == 'grp' && b.ref_target == 'nat' ? 1 :
    
        (a.ref_target == 'nat' && b.ref_target == 'ecr' ? -1 :
         (a.ref_target == 'ecr' && b.ref_target == 'nat' ? 1 :
    
          (a.ref_target == 'nat' && b.ref_target == 'ord' ? -1 :
           (a.ref_target == 'ord' && b.ref_target == 'nat' ? 1 :
    
            (a.ref_target == 'loc' && b.ref_target == 'grp' ? -1 :
             (a.ref_target == 'grp' && b.ref_target == 'loc' ? 1 :
    
              (a.ref_target == 'loc' && b.ref_target == 'ecr' ? -1 :
               (a.ref_target == 'ecr' && b.ref_target == 'loc' ? 1 :
    
                (a.ref_target == 'loc' && b.ref_target == 'ord' ? -1 :
                 (a.ref_target == 'ord' && b.ref_target == 'loc' ? 1 :
    
                  (a.ref_target == 'ecr' && b.ref_target == 'grp' ? -1 :
                   (a.ref_target == 'grp' && b.ref_target == 'ecr' ? 1 :
    
                    (a.ref_target == 'grp' && b.ref_target == 'ord' ? -1 :
                     (a.ref_target == 'ord' && b.ref_target == 'grp' ? 1 :
    
                      (a.ref_target == 'ecr' && b.ref_target == 'ord' ? -1 :
                       (a.ref_target == 'ord' && b.ref_target == 'ecr' ? 1 :
                        (a.start <= b.start ? -1 : 
                         (a > b.start ? 1 :
                          (a.order <= b.order ? -1 : 0 )))))))))))))))))))))));
    
    				   return valeur;
});

console.log(slides);

```


Système de gestion des écrans déportés.

Le système est une web-app PHP/HTML/CSS/JAVASCRIPT.

La version de travail actuelle est dev-Beta-1

Gildas, j'ai créé une branche (slideshow-glidas désolé pour la faute) pour que tu puisses insérer tes modifications.


http://alainericgauthier.com/git/aide_memoire_git

https://help.github.com

https://help.github.com/articles/ignoring-files

https://help.github.com/articles/using-sublime-text-2-as-your-default-editor

http://scottchacon.com/2011/08/31/github-flow.html


Pour un meilleur mode MVC :

http://forum.phpfrance.com/php-debutant/demande-explication-sur-flag-qsa-dans-htaccess-t255298.html

Librairies interface :

iCanHaz
- http://icanhazjs.com
- https://github.com/janl/mustache.js
- https://github.com/bobthecow/mustache.php/wiki/Mustache-Tags
 
Timeline :
- http://almende.github.io/chap-links-library/timeline.html
- http://almende.github.io/chap-links-library/js/timeline/doc/

Fancybox :
- https://github.com/fancyapps/fancyBox
- http://www.fancyapps.com/fancybox/

Dynamic JS:
- http://www.siteduzero.com/informatique/tutoriels/ajax-et-l-echange-de-donnees-en-javascript/charger-un-objet-json-statique
- http://headjs.com
- http://requirejs.org/docs/api.html
