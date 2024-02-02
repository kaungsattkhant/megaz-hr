import axios from 'axios';

export async function getApiData(payload)
{
    let response = null;
    response = await axios.get(payload.url, {
        headers: {
            'Content-Type': 'multipart/formdata',
            'Authorization': 'Bearer ' + payload.token,
            'Access-Control-Allow-Origin': '*'
        }
    }).then(function(response){
        let responsePayload = { data: null, message: null, success: response.data.success };
        if(response.data.data){
            responsePayload.data = response.data.data;
        }
        if(response.data.message){
            responsePayload.message = response.data.message;
        }

        return responsePayload;
    }).catch(function (error) {
        let responsePayload = { message: 'Server error', success: false };
        if(error.response){
            console.error('Error status code', error.response.status);
        }
        if(error.response.data.message){
            console.error('Error message', error.response.data.message);
            responsePayload = { message: error.response.data.message };
        }

        return responsePayload;
    });

    return response;
}

export async function postApiData(payload)
{
    let response = null;
    response = await axios.post(payload.url, payload.form_data, {
        headers: {
            'Content-Type': 'multipart/formdata',
            'Authorization': 'Bearer ' + payload.token,
            'Access-Control-Allow-Origin': '*'
        }
    }).then(function(response){
        let responsePayload = { data: null, message: null, success: response.data.success };
        if(response.data.data){
            responsePayload.data = response.data.data;
        }
        if(response.data.message){
            responsePayload.message = response.data.message;
        }

        return responsePayload;
    }).catch(function (error) {
        let responsePayload = { message: 'Server error', success: false };
        if(error.response){
            console.error('Error status code', error.response.status);
        }
        if(error.response.data.message){
            console.error('Error message', error.response.data.message);
            responsePayload = { message: error.response.data.message };
        }

        return responsePayload;
    });

    return response;
}

export async function putApiData(payload)
{
    let response = null;
    response = await axios.put(payload.url, payload.form_data, {
        headers: {
            'Content-Type': 'multipart/formdata',
            'Authorization': 'Bearer ' + payload.token,
            'Access-Control-Allow-Origin': '*'
        }
    }).then(function(response){
        let responsePayload = { data: null, message: null, success: response.data.success };
        if(response.data.data){
            responsePayload.data = response.data.data;
        }
        if(response.data.message){
            responsePayload.message = response.data.message;
        }

        return responsePayload;
    }).catch(function (error) {
        let responsePayload = { message: 'Server error', success: false };
        if(error.response){
            console.error('Error status code', error.response.status);
        }
        if(error.response.data.message){
            console.error('Error message', error.response.data.message);
            responsePayload = { message: error.response.data.message };
        }

        return responsePayload;
    });

    return response;
}

export async function deleteApiData(payload)
{
    let response = null;
    response = await axios.delete(payload.url, {
        headers: {
            'Authorization': 'Bearer ' + payload.token,
            'Access-Control-Allow-Origin': '*'
        }
    }).then(function(response){
        let responsePayload = { message: null, success: response.data.success };
        if(response.data.message){
            responsePayload.message = response.data.message;
        }

        return response.data;
    }).catch(function (error) {
        let responsePayload = { message: 'Server error', success: false };
        if(error.response){
            console.error('Error status code', error.response.status);
        }
        if(error.response.data.message){
            console.error('Error message', error.response.data.message);
            responsePayload = { message: error.response.data.message };
        }

        return responsePayload;
    });

    return response;
}
