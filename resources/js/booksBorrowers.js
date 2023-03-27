var options = {
    series: [
        {
            name: "Books",
            data: [5, 12, 36, 9, 28, 41, 3, 17, 22, 49, 8, 31],
        },
        {
            name: "Borrowers",
            data: [50, 27, 11, 6, 19, 43, 32, 14, 38, 9, 22, 47],
        },
    ],
    chart: {
        height: 450,
        type: "bar",
        dropShadow: {
            enabled: true,
            color: "#000",
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2,
        },
        toolbar: {
            show: true,
            offsetY: -20,
            tools: {
                download: true,
                selection: true,
                zoom: true,
                zoomin: true,
                zoomout: true,
                pan: true,
                reset: true,
            },
        },
        zoom: {
            enabled: true,
        },
    },
    colors: ["#48523e", "#c5b32a"],
    dataLabels: {
        enabled: true,
    },
    stroke: {
        curve: "smooth",
    },
    title: {
        text: "Books and Borrowers Graph",
        align: "left",
        offsetY: 10,
    },
    grid: {
        borderColor: "#e7e7e7",
        row: {
            colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
            opacity: 0.5,
        },
        padding: {
            left: 30, // or whatever value that works
            right: 30, // or whatever value that works
        },
    },
    markers: {
        size: 1,
    },
    xaxis: {
        categories: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ],
        tickPlacement: "on",
        title: {
            text: "Month",
        },
    },
    yaxis: {
        title: {
            text: "Number of Students",
        },
    },
    legend: {
        position: "top",
        horizontalAlign: "right",
        floating: true,
        offsetY: -25,
        offsetX: -5,
    },
};

export default options;
