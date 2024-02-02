// Sets an item with a Key to local storage
export function saveStorage(key, data)
{
    localStorage.setItem(key, JSON.stringify(data));
    return true;
}

// Looks for a local storage item and returns if present
export function getStorage(key)
{
    let storedData = localStorage.getItem(key);
    if(storedData == null){
        return null;
    }

    return storedData;
}

// Get stored item with id
export function getStorageItem(key, id)
{
    let storedData = localStorage.getItem(key);
    if(storedData == null){
        return null;
    }
    const data = JSON.parse(storedData);

    return data[id];
}

// Clear a single item with key
export function removeStorage(key)
{
    localStorage.removeItem(key);
    return true;
}

// Clear the whole local storage
export function clearStorage()
{
    localStorage.clear();
    return true;
}
