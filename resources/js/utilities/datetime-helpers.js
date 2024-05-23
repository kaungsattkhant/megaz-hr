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

export function convertToFriendlyDateTime(dbDateString)
{
    const date = new Date(dbDateString);
    const options = {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
      };

      const formattedDate = date.toLocaleDateString('en-US', options);
      const formattedTime = date.toLocaleTimeString('en-US', options);

      return formattedTime;
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

export function getElapsedMoments(dbDateTimeString)
{
    const timestamp = new Date(dbDateTimeString);
    const now = new Date();
    const diff = now.getTime() - timestamp.getTime();
    const seconds = Math.floor(diff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    if (days > 0) {
        return days === 1 ? '1 day ago' : `${days} days ago`;
    } else if (hours > 0) {
        return hours === 1 ? '1 hour ago' : `${hours} hours ago`;
    } else if (minutes > 0) {
        return minutes === 1 ? 'one minute ago' : `${minutes} minutes ago`;
    } else {
        return 'just now';
    }
}
