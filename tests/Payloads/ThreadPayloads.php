<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class ThreadPayloads
{
    public static function getThread(int $id): string
    {
        return json_encode(static::thread($id));
    }

    public static function getThreads(int $pageNumber, int $totalElements): string
    {
        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        // Create embedded resources
        $threads = array_map(function ($id) {
            return static::thread($id);
        }, range(1, $pageElements));

        $data = [
            '_embedded' => [
                'threads' => $threads,
            ],
            'page' => [
                'size' => $pageSize,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
                'number' => $pageNumber,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.helpscout.net/v2/conversations/1/threads',
                ],
                'next' => [
                    'href' => 'https://api.helpscout.net/v2/conversations/1/threads?page=2',
                ],
                'first' => [
                    'href' => 'https://api.helpscout.net/v2/conversations/1/threads?page=1',
                ],
                'last' => [
                    'href' => "https://api.helpscout.net/v2/conversations/1/threads?page=$totalPages",
                ],
                'page' => [
                    'href' => 'https://api.helpscout.net/v2/conversations/1/threads{?page}',
                    'templated' => true,
                ],
            ],
        ];

        if ($pageElements === 0) {
            // The _embedded key is not set when empty
            unset($data['_embedded']);
        }

        return json_encode($data);
    }

    private static function thread(int $id): array
    {
        return [
            'id' => $id,
            'type' => 'customer',
            'status' => 'active',
            'state' => 'published',
            'action' => [
                'type' => 'manual-workflow',
                'text' => 'You ran the Assign to Spam workflow',
            ],
            'body' => 'Need Help',
            'source' => [
                'type' => 'email',
                'via' => 'user',
            ],
            'createdBy' => [
                'id' => 6857,
                'type' => 'customer',
            ],
            'assignedTo' => [
                'id' => 1234,
                'type' => 'team',
                'first' => 'Jack',
                'last' => 'Sprout',
                'email' => 'bear@acme.com',
            ],
            'savedReplyId' => 17142,
            'customer' => [
                'id' => 256,
                'email' => 'vbird@mywork.com',
            ],
            'to' => [
                'bird@normal.com',
            ],
            'cc' => [
                'bear@normal.com',
            ],
            'bcc' => [
                'bear@secret.com',
            ],
            'createdAt' => '2017-04-21T14:39:56Z',
        ];
    }

    public static function getThreadSource(): array
    {
        return [
            'original' => <<<EOF
"Correlation-Id: f8d4c6c8-bd1c-44df-ad4a-513aa17674f9\nX-Hs-Spam-Score: -4.463879\nX-Spam-Checks: {\"ARC_NA\":{\"score\":0},\"R_DKIM_ALLOW\":{\"score\":-0.2,\"options\":[\"helpscout.com:s=google\"]},\"RWL_MAILSPIKE_POSSIBLE\":{\"score\":0,\"options\":[\"54.221.85.209.rep.mailspike.net : 127.0.0.17\"]},\"FROM_HAS_DN\":{\"score\":0},\"R_SPF_ALLOW\":{\"score\":-0.2,\"options\":[\"+ip4:209.85.128.0\\/17\"]},\"MIME_GOOD\":{\"score\":-0.1,\"options\":[\"multipart\\/alternative\",\"text\\/plain\"]},\"PREVIOUSLY_DELIVERED\":{\"score\":0,\"options\":[\"my-sdk-mailbox@helpscout.net\"]},\"TO_DN_NONE\":{\"score\":0},\"RCPT_COUNT_ONE\":{\"score\":0,\"options\":[\"1\"]},\"DKIM_TRACE\":{\"score\":0,\"options\":[\"helpscout.com:+\"]},\"DMARC_POLICY_ALLOW\":{\"score\":-0.5,\"options\":[\"helpscout.com\",\"none\"]},\"RCVD_IN_DNSWL_NONE\":{\"score\":0,\"options\":[\"54.221.85.209.list.dnswl.org : 127.0.5.0\"]},\"NEURAL_HAM\":{\"score\":-0,\"options\":[\"-1.000\",\"0\"]},\"FROM_EQ_ENVFROM\":{\"score\":0},\"MIME_TRACE\":{\"score\":0,\"options\":[\"0:+\",\"1:+\",\"2:~\"]},\"IP_SCORE\":{\"score\":-3.463879,\"options\":[\"ip: (-9.05), ipnet: 209.85.128.0\\/17(-4.55), asn: 15169(-3.64), country: US(-0.09)\"]},\"ASN\":{\"score\":0,\"options\":[\"asn:15169, ipnet:209.85.128.0\\/17, country:US\"]},\"HFILTER_HOSTNAME_UNKNOWN\":{\"score\":0},\"RCVD_TLS_ALL\":{\"score\":0},\"RCVD_COUNT_TWO\":{\"score\":0,\"options\":[\"2\"]}}\nX-Incoming-Source: email\nReturn-path: <customer-a@helpscout-customer.com>\nEnvelope-to: my-sdk-mailbox@helpscout.net@helpscout.net\nDelivery-date: Tue, 09 Jun 2020 13:26:55 +0000\nReceived: from mail-wr1-f54.google.com ([209.85.221.54])\n\tby mx1-1d.helpscout.net with esmtps (TLS1.2:RSA_AES_128_CBC_SHA1:128)\n\t(Exim 4.82)\n\t(envelope-from <customer-a@helpscout-customer.com>)\n\tid 1jieHH-0006c3-6I\n\tfor my-sdk-mailbox@helpscout.net; Tue, 09 Jun 2020 13:26:55 +0000\nReceived: by mail-wr1-f54.google.com with SMTP id x6so21248147wrm.13\n        for <my-sdk-mailbox@helpscout.net>; Tue, 09 Jun 2020 06:26:55 -0700 (PDT)\nDKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;\n        d=helpscout.com; s=google;\n        h=mime-version:from:date:message-id:subject:to;\n        bh=6Z7hq0L6aBpZwdkU1h+vKuK04L9HZukkazoBEn3amIE=;\n        b=WpvlC+d2vjMABkSxaupG4QpOHuMG52tZPAylx1V1ixe0GJRs14FdW7ZHkL9/MHiTNR\n         B+U8lAoI7l8G/Q4kXtt7lHVNNyes2Y3vjZ1GG6+N3rJILmQSPiqNnZTx4517Y4qwXY6c\n         QhDeYFBg6BMsJQBMKVi9jgo90NWH7zYXM6Y0I=\nX-Google-DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;\n        d=1e100.net; s=20161025;\n        h=x-gm-message-state:mime-version:from:date:message-id:subject:to;\n        bh=6Z7hq0L6aBpZwdkU1h+vKuK04L9HZukkazoBEn3amIE=;\n        b=rPcVBNWjf2s/NRHyHQGli2FAX8CzkeFEeJuPsLBSamHHnFQur1pgomPAVNNQ7HhQOh\n         E/Gj1Mqz58Tkwr+3EQL9ZltRls+pYOw5p8jE0cRxjXaBiYRQ61XBinQKvTfdM7pCAV1U\n         gKMd0lfSxmp01SZb8/yzfjd90OtmlDJDw8GvkUAfrxW28Ztt1VEIPSXHqI4L1YTlQ6YG\n         tSL8C+SUmJ9JjNFiJH5PQ+Xuw8g0nnKtbrvzffoR9lAXWzcKl8MvHy+YWa2YOv6DZ9gw\n         hmNBDDcdJSKdz3mt3HGMLLLykuIwQa5W99zPNp16gPHXjf6Tz4og4u19Q3SWZmUCxn1B\n         5xDg==\nX-Gm-Message-State: AOAM5322pI+asDDh0bhopd5OvnY+UTrXuhuwke8ufO1lK/Pd7w779xgN\n\toMGCoSmXSBVh4oqDy1vbe4VqegnWx+EF44tm78j51xuiovY=\nX-Google-Smtp-Source: ABdhPJxDRtJa5jCAWmHe3aWcSZEQi4Cg100t3mt4unaRwbjKbznbs+ZB9cVMq7O/tnEocZpyloL0wUx4wwlahOaREzU=\nX-Received: by 2002:adf:df03:: with SMTP id y3mr4279506wrl.376.1591709214305;\n Tue, 09 Jun 2020 06:26:54 -0700 (PDT)\nMIME-Version: 1.0\nFrom: Customer A <customer-a@helpscout-customer.com>\nDate: Tue, 9 Jun 2020 09:26:43 -0400\nMessage-ID: <LKAOED4vcvekvDFUIDOV6v2nYKKD_SoevFJ6G5xYDY77KWaxoRj+9mnA@mail.gmail.com>\nSubject: Pizza party request\nTo: my-sdk-mailbox@helpscout.net\nContent-Type: multipart/alternative; boundary=\"0000000000004e123105a7a6ae81\"\n\n--0000000000004e123105a7a6ae81\nContent-Type: text/plain; charset=\"UTF-8\"\n\nI love pizza partys\n\n--0000000000004e123105a7a6ae81\nContent-Type: text/html; charset=\"UTF-8\"\n\n<div dir=\"ltr\"><span style=\"color:rgb(37,53,64);font-family:&quot;Aktiv Grotesk&quot;,&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:14px\">There's nothing like a good pizza</span><br></div>\n\n--0000000000004e123105a7a6ae81--\n"
EOF,
        ];
    }
}
