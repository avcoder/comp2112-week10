# zingcharts

## Getting started

1. Goto: https://www.zingchart.com/docs/getting-started/your-first-chart/
1. Slim it to 1 series
1. Give labels A B C D

   ```js
   'scale-x': {
    labels: ['A', 'B', 'C', 'D'],
    item: {
        'font-color': '#000',
        'font-size': '40px',
    },
   },
   ```

1. Integrate it with our votes data
1. Odd: why are the bars inaccurate if tally's value is a string?
1. Use parseInt() to convert string to number

## JSON in jsonbin.io

1. use json validator
1. once validated, get link by clicking copy

## fetch data

1. use async/await/fetch to get data from 'https://gist.githubusercontent.com/avcoder/e9e274c5a518df1870e86706bb954c39/raw/1636ed24d4fb15563d75d94e7f9b428fd7e7d63f/votes.json'

## simulate polling

1. add setTimeout

### Finished code

    ```js
    let votes = [{"voted": 'a', "tally": 1},
    {"voted": 'b', "tally": 2},
    {"voted": 'c', "tally": 3},
    {"voted": 'd', "tally": 4}];

    const vote_endpoint = 'https://gist.githubusercontent.com/avcoder/e9e274c5a518df1870e86706bb954c39/raw/1636ed24d4fb15563d75d94e7f9b428fd7e7d63f/votes.json';

    async function getVotes() {
        const response = await fetch(vote_endpoint);
        votes = await response.json();
        votes.forEach(vote => vote.tally = parseInt(vote.tally));
        drawChart();
    }

    setTimeout(() => {
        getVotes();
    }, 3000)

    drawChart();

    function drawChart() {

        let chartData = {

            type: 'bar', // Specify your chart type here.
            title: {
             text: 'Voting results' // Adds a title to your chart
            },
            legend: {}, // Creates an interactive legend
            series: [ // Insert your series data here.
            { values: [votes[0].tally, votes[1].tally, votes[2].tally, votes[3].tally]}
            ],
            'scale-x': {
                labels: ['A', 'B', 'C', 'D'],
                item: {
                    'font-color': '#000',
                    'font-size': '40px',
                },
            },
            plot: {
                animation:{
                    effect: 4,
                    method: 5,
                    speed: 700,
                    sequence: 1
                }
            }
        };

        zingchart.render({ // Render Method[3]
            id: 'chartDiv',
            data: chartData,
            height: 400,
            width: 600
        });
    }
    ```

# Create vote page

## create html page with 4 forms

1. Open vote.html (from my github repo)
1. Enter hidden input fields `<input type="hidden" name="vote" value="a" />`
1. Fill in method - GET vs POST discussion
1. show .http via REST client extension
1. show postman tool
1. Fill in action - what url should process this request?

# Create php page to process votes

## concept of serverless

1. serverless discussion

## phpfiddle.org

    ```php
    <?php
    $votesA = [
        "voted" => "a",
        "tally" => "3",
    ];

    $votes = [$votesA, $votesB, $votesC, $votesD];

    echo json_encode($votes);

    ?>
    ```

1. Review: create vote.php `<?php phpinfo() ?>`
