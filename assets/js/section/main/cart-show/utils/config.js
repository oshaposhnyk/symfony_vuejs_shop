const HEADERS = {
    headers: {

        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
};

const HEADERS_PATCH = {
    headers: {
        'Content-Type': 'application/merge-patch+json',
        'Accept': 'application/json'
    }
};

export { HEADERS, HEADERS_PATCH };