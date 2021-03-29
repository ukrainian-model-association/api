#!/usr/bin/env bash

sendRequest() {
    local username="${1}"
    local url="https://www.instagram.com/${username}/?__a=1"

    curl "${url}" \
        -H 'authority: www.instagram.com' \
        -H 'cache-control: max-age=0' \
        -H 'sec-ch-ua: "Google Chrome";v="89", "Chromium";v="89", ";Not A Brand";v="99"' \
        -H 'sec-ch-ua-mobile: ?0' \
        -H 'upgrade-insecure-requests: 1' \
        -H 'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_2_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36' \
        -H 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9' \
        -H 'sec-fetch-site: none' \
        -H 'sec-fetch-mode: navigate' \
        -H 'sec-fetch-user: ?1' \
        -H 'sec-fetch-dest: document' \
        -H 'accept-language: uk-UA,uk;q=0.9,ru;q=0.8,en;q=0.7' \
        -H 'cookie: mid=YGG0LwAEAAGFYitL2V85P-wXEFlv; ig_did=156EB1F6-8255-4DE1-B09A-EA62ADD0B073; ig_nrcb=1; csrftoken=EjqIwMZPeCW0YMGbG6ol55wJgHJ44Ts0; ds_user_id=332903301; sessionid=332903301%3A2zKKAKEtK3Io9h%3A10; shbid=3433; shbts=1617015929.6546795; rur=PRN' \
        --compressed
}

sendRequest "$1"
