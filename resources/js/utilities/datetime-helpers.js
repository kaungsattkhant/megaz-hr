export function getCurrentDate()
{
    return new Date().toISOString().split('T')[0];
}

export function getCurrentTime(hourOffset = 6, minuteOffset = 30)
{
    let currentUnixTimeStamp = Math.floor(Date.now());
    let hourOffsetTimestamp = (hourOffset * 3600) * 1000;
    let minuteOffsetTimestamp = (minuteOffset * 60) * 1000;
    let newUnixTimeStamp = currentUnixTimeStamp + hourOffsetTimestamp + minuteOffsetTimestamp;
    let dateTime = new Date(newUnixTimeStamp).toISOString().split('T');
    let timePart = dateTime[1];
    let time = timePart.split('.')[0];

    return time;
}

export function getCurretDateTime(hourOffset = 6, minuteOffset = 30)
{
    let currentUnixTimeStamp = Math.floor(Date.now());
    let hourOffsetTimestamp = (hourOffset * 3600) * 1000;
    let minuteOffsetTimestamp = (minuteOffset * 60) * 1000;
    let newUnixTimeStamp = currentUnixTimeStamp + hourOffsetTimestamp + minuteOffsetTimestamp;
    let dateAndTime = new Date(newUnixTimeStamp).toISOString().split('T');
    let date = dateAndTime[0];
    let timePart = dateAndTime[1];
    let time = timePart.split('.')[0];

    return date + ' ' + time;
}

export function convertToFriendlyDate(dbDateString)
{
    const unixTimestamp = Date.parse(dbDateString);
    const months = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];

    const date = new Date(unixTimestamp);
    const month = months[date.getMonth()];
    const day = ("0" + date.getDate()).slice(-2);
    const year = date.getFullYear();

    return `${month} ${day}, ${year}`;
}

export function getFirstDate(dbDateString)
{
    let date = new Date(dbDateString);
    // Set the date to 1 to get the first date of the month
    date.setDate(1);
    // Format the result as "YYYY-mm-dd"
    let firstDateOfMonth = date.toISOString().split('T')[0];

    return firstDateOfMonth;
}

export function convertToMonth(dbDateString)
{
    const unixTimestamp = Date.parse(dbDateString);
    const months = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];

    const date = new Date(unixTimestamp);
    const month = months[date.getMonth()];
    const year = date.getFullYear();

    return `${month}, ${year}`;
}
