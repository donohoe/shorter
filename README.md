# Shorter

## What is this?

It is a very basic URL shortener plugin that uses the original Post ID as the basis for a short URL.

You can find the latest version here:
https://github.com/donohoe/shorter

## What does that mean?

It means that if the URL for a WordPress post is:

https://restofworld.org/2021/hi-im-disaster-bot/

And the Post ID for that post is `19832`

Then you can use this URL:

https://restofworld.org/s/19832

as a shorter URL that automatically redirects to the canonical (original) post URL.

And yes, you can also just use native funtionality already present in WordPress and use this:

 http://restofworld.org/?p=19832

 But I find that just horrible. Aesthetically, and as a URL. I have a strong negative reaction to it. If you don't, then by all means uninstall this plugin and go with that. Somtimes these things are subjective, but if that looks okay to you, then you're just plain wrong. We could probably never be friends IRL.

## Can I do all sorts of tracking with this?

Yes and No. Nothing intended to be privacy invasive on the individiual level.

### Referer ###
When WordPress redirects you it sets the header 'X-Redirect-By' to 'WordPress'. This plugin changes that from 'WordPress' to 'Shorter' so you know the reader came via the plugin and therfore a short URL.

If the HTTP-REFERER value available, the host will be appended using a `-` (dash) as a sperator.

For example:

If a user came from `https://example.com/what/the/duck` and that shows up in `$_SERVER['HTTP_REFERER']` then the header value for `X-Redirect-By` will be `Shorter-example.com`. If the value for HTTP_REFERER is empty it will just be `Shorter`.

### Source ###

You can adjust short URLs so you use them in a variety of places and see where traffic cultimately came from.

For example:

Lets say your short URL for your post is: `https://example.com/s/12345` and your full post URL is: `https://example.com/post/how-about-a-nice-cup-of-tea`

For the link you bost to Twitter you can use: `https://example.com/s/12345-twits`
For your link that you post to Friendster you can use: `https://example.com/s/12345-fster`

They will both redirect to the the long URL but the text-string will be appended as a hash to the URLs respectively.

```
https://example.com/post/how-about-a-nice-cup-of-tea#s=twits
https://example.com/post/how-about-a-nice-cup-of-tea#s=fster
```

You can use Javascript to pass this to your client-side analytics library. One way to get this value is:

```
let source = location.hash.replace('#s=', '')
```

## Testing

If you need to debug anything you can append a debug paramater to your short URL. Juts make sure you include the debug key (and that you change that value to your own unique property)

With debugging enabled, instead of redirecting to the post that corresponds to the ID, it will output the main varables it depends upon (id, permalink, redirected by header) as JSON.

Example:
`https://example.com/s/12345-twits?debug=xyz`

Output:
```
{
    "post_id": 12345,
    "permalink": "http:\/\/example.com\/post\/how-about-a-nice-cup-of-tea#s=twits",
    "redirect_by": "Shorter-referer-host-if-any.org"
}
```

## Should I use a URL Shortener?

Short answer (no pun intended), no.

However if you must then this isn't a terrible option. The Post ID is always unique to a post.

In general you should avoid URL shortener servives as they have often be used to hide URL paramaters and other tracking junk. They real need for a shortener died years ago when Twitter stopped counting character length of a URL when composing tweets.

## Potential Future Changes ##

Shout if any of these seem useful... 

* Add compoennt to Post editor UI to publizize the short URL
* Option to objuscate Post IDs by using hexadecimal version
* Faciliate server or client-side call to Google Analytics (or other)