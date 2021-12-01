<?php

/*
    Test page for Redwing Interactive
    November 2021, Caractacus Downes
    A series of simple tests to assess familiarity and capability with some core languages and techniques.
*/

$mcuFilms = json_decode(file_get_contents('mcu.json'));

/*

For this test you need this file and the supplied mcu.json file in the same directory.

This page lists (some of) the films from the Marvel Cinematic Universe, including their title, director(s), main characters and an image.

Task 1:     Modify the css of the page to show the film blocks in rows across the page, making whatever changes you think will improve the overall appearance of the page.
            The layout should be responsive so that you see an appropriate number of films in a row depending on screen size.

Task 2:     The $mcuFilms php variable holds data about more of the MCU films.  Populate the film blocks from the data in the variable when the page loads instead of having it hard coded.

Task 3:     There are two select boxes at the top of the page.
            One of them should filter the visible films by character (so if you select Iron Man from the select you should only see films in which Iron Man appears).
            The other should sort the films by either year or title.
            Using JavaScript and jQuery, populate the select filter from the $mcuFilms data and make the two selects work.

*/


?>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Redwing Test</title>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;600&display=swap" rel="stylesheet">

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

    <style>
        body,
        select {
            font-family: 'Raleway', sans-serif;
        }

        h2 {
            margin: 0.25em;
        }

        p {
            margin: 0.25em;
        }

        #divWrapper {
            padding: 20px 40px;
        }

        nav {
            padding: 20px;
        }

        nav select {
            font-size: 1em;
            margin: 0 1em;
            padding: 0.25em;
            border-radius: 4px;
        }

        main div ul {
            list-style: none;
            padding: 0;
        }
    </style>

</head>

<body>

    <div id="divWrapper" class="max-w-screen px-12">

        <header>
            <h1>Marvel Cinematic Universe</h1>
        </header>

        <nav>
            <div>

                <label for="selCharacterFilter">Filter by Character</label>
                <select id="selCharacterFilter">
                    <option value="all">Show All</option>
                </select>

                <label for="selSort">Sort</label>
                <select id="selSort">
                    <option value="year">Year</option>
                    <option value="title">Title</option>
                </select>

            </div>
        </nav>

        <main class="w-full flex justify-center">

                <!-- All Movies -->
                <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 col-span-4 w-full" id="movieUl">
                    <?php foreach ($mcuFilms as $key => $film) : ?>
                        <li class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200 movieListEl" 
                            id="film_<?= $key; ?>"
                            data-movie
                            data-release-year="<?= $film->year ?>"
                            data-movie-title="<?= $film->title ?>"
                        >
                            <div class="flex-1 flex flex-col justify-center items-center p-4">

                                <!-- Image -->
                                <div style="height: 240px; width: 180px;" class="bg-gray-100 flex items-center justify-center">
                                    <img class="flex-shrink-0 mx-auto object-cover rounded-md max-w-full max-h-full" 
                                        src="<?= $film->image ?>" 
                                        alt="<?= $film->title ?>"
                                    >
                                </div>

                                <!-- Title -->
                                <h3 class="mt-2 text-gray-900 text-sm font-bold">
                                    <?= $film->title; ?>
                                </h3>

                                <!-- Further Info -->
                                <dl class="mt-1 flex-grow flex flex-col justify-between">
                                    <dt class="sr-only">Title</dt>
                                    <dd class="text-gray-500 text-xs">
                                        Directed by
                                        <?php foreach ($film->directors as $dkey => $director) : ?>
                                            <span id="director_<?= $dkey; ?>">
                                                <?= $director; ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </dd>
                                </dl>
                                
                                <!-- Release Date -->
                                <p class="text-sm text-gray-500">
                                    Released <?= $film->year ?>
                                </p>
                            </div>
                            <div>
                                <div class="-mt-px flex divide-x divide-gray-200">
                                    <div class="-ml-px w-0 flex-1 flex">
                                        <!-- Characters -->
                                        <div class="grid grid-cols-2 gap-y-1 gap-x-2 py-1 px-2 items-center justify-center overflow-hidden w-full">
                                            <h4 class="col-span-2 font-medium text-sm">Characters</h4>
                                            <input type="hidden" id="characters_<?= $key ?>" data-characters="<?= json_encode($film->characters) ?>">
                                            <?php foreach ($film->characters as $ckey => $character) : ?>
                                                <div class="col-span-1 border border-gray-200 rounded-md text-xs" id="<?= $ckey; ?>">
                                                    <h4><?= $character; ?></h4>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </main>

    </div>

    <script id="mcu_data" type="application/json"><?php include('mcu.json'); ?></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const jsonData = JSON.parse(document.getElementById('mcu_data').textContent)
            const charFilter = document.getElementById('selCharacterFilter');
            const selSort = document.getElementById('selSort');

            // Creates a unique, alpha-sorted array of characters
            uniqueChars = [...jsonData.reduce((charSet,{characters}) => (characters.forEach(charSet.add, charSet), charSet), new Set())].sort()

            // Appends option els to the character select
            uniqueChars.forEach(char => {
                const node = document.createElement("OPTION");
                const textnode = document.createTextNode(char);
                node.appendChild(textnode);
                node.setAttribute('key', char);
                charFilter.appendChild(node);
            });

            // Filters the movies based on the character filter value
            charFilter.addEventListener('change', e => {
                bulkRemoveClass("movieListEl", ['hidden']);

                const value = e.target.value;
                if (value == "all") return;

                nonMatchingMovies = [];
                jsonData.forEach((movie, key) => {
                    if (!movie.characters.includes(value)) {
                        nonMatchingMovies.push(key);
                    }
                })

                nonMatchingMovies.forEach(key => {
                    let el = document.getElementById(`film_${key}`);
                    el.classList.add('hidden');
                });
            });

            selSort.addEventListener('change', e => {
                const value = e.target.value;
                const allMovies = Array.from(document.querySelectorAll('[data-movie]'));
                if (value == "title") {
                    let sorted = allMovies.sort(sortTitle);
                    sorted.forEach(e => {
                        document.getElementById('movieUl').appendChild(e);
                    })
                } else {
                    let sorted = allMovies.sort(sortYear);
                    sorted.forEach(e => {
                        document.getElementById('movieUl').appendChild(e);
                    })
                }
            });

            /**
             * Bulk-removes classes from searched elements
             * 
             * @param string movieClass
             * @param array classesToRemove
             * 
             * @return void
             */
            const bulkRemoveClass = (movieClass, classesToRemove) => {
                classesToRemove.forEach(ctr => {
                    const els = document.getElementsByClassName(movieClass);
                    Array.prototype.forEach.call(els, el => {
                        el.classList.remove(ctr);
                    });
                })
            }
        });
    </script>

</body>

</html>