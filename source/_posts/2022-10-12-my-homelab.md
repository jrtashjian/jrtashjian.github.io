---
extends: _layouts.post
title: My Homelab
date: 2022-10-12
categories: [homelab]
---
In February 2021, I purchased my first enterprise grade rack-mounted server. I had a simple reason for doing so: I wanted better resiliency for my important digital archives, and the ability to run an extra service or two alongside it. Now a year and a half later I have a 18U server rack with multiple servers and many self-hosted services.

I've been fascinated with computer hardware since I was a child. It was said that my grandfather used to say "computers will never take off" only to later run an entire business selling and repairing them until he passed. He was a driving force for my interest and going to his little computer shop and tinkering or going out with him on jobs was my favorite thing to do.

Although my interest was in hardware I ended up going the software development route and hardware became secondary. I still build my own computers and doing the research to learn about new hardware. But when it comes to self-hosting I've always been of the mindset that we should own our data and that stance is justified more and more each day a product or service is shutdown, personal and private data is found to be used in undisclosed ways, or when any person is cutoff from their data for any reason or whim.

## The Journey

My homelab journey began by simply wanting a better file backup system for my photo library and important digitized documents. Like most people I started with just some USB hard drives to make sure I had a second copy as well as a mixture of third-party services that I didn't utilize entirely (Google Drive/Photos, iCloud, and Dropbox). To improve my setup I bought a [WD EX2100 NAS system](https://products.wdc.com/library/AAG/ENG/4078-705150.pdf) which provided similar software for easily syncing documents and photos from my desktop, laptop, and phone to my own drives. This also began my journey into a RAID system for increasing resiliency (RAID is not a backup!) with a simple 6TB mirrored drive configuration.

That system ran well for a many years but when I started tinkering I figured out that the NAS had a way to install applications such as [Plex Media Server](https://www.plex.tv/) and with some work I was also able to gain shell access and get [Docker](https://www.docker.com/) running. As my data needs grew and my tinkering increased I realized the dual-core ARM processor and 1GB of memory was no longer working for me.

## The Upgrade

For some reason I thought enterprise gear was unattainable because of it's enormous cost. When looking at sites like Dell, the cost of a single server can easily go into the thousands of dollars. I don't even remember how but I ran across the subreddit [r/homelab](https://www.reddit.com/r/homelab) and [r/selfhosted](https://www.reddit.com/r/selfhosted) and was exposed to many more open source projects I could host myself and an entire community who re-purpose decommissioned enterprise server gear for their own homelabs. I consumed as much information as I could and dreamed about server racks in my house running all sorts of things! But I knew I had to start small and there was a lot more I needed to learn along the way. I picked up a Dell R210 II to eventually become a router and a Dell R420 to become my new NAS server. I also had an older MacBook Pro and MacBook Air laying around that I started using as Docker hosts.

[![](/assets/images/homelab-2021-02.webp "A Dell Poweredge R210 II, Dell Poweredge R420, and Apple MacBooks stacked on top of each other")](/assets/images/homelab-2021-02.webp)

## Current setup

Almost two years later I've purchased a server rack to hold everything, a disk shelf to increase my storage capacity, and a couple more servers to tinker and host services.

[![](/assets/images/homelab-2022-08.webp "The front and back view of an 18U server rack with servers and networking equipment within")](/assets/images/homelab-2022-08.webp)

### Dell PowerEdge R210 II
- Intel Xeon E3-1230 3.20 GHz (4 cores / 8 threads)
- 16 GB Memory (PC3-12800R)

This server is running [Proxmox](https://www.proxmox.com/en/proxmox-ve) for virtualization and is focused on the essential services required for the network. I’m running [OPNsense](https://opnsense.org) as my network router and firewall, [Authentik](https://goauthentik.io) as the Identity Provider for my self-hosted services, and a virtual machine Docker host running [Flame](https://github.com/pawelmalak/flame) and [Uptime Kuma](https://github.com/louislam/uptime-kuma).

### Dell PowerEdge R420
- 2x Intel Xeon E5-2430 2.20 GHz (6 cores / 12 threads)
- 96 GB Memory (PC3-10600R)
- 4x 3TB 7.2k SAS 3.5" HDD

This server is running [TrueNAS Scale](https://www.truenas.com/truenas-scale/) and using ZFS for RAID. The four drives in the system are configured as a raidz2 pool. This pool mirrors the most important data from the NetApp DS4246 (which is directly connected to the R420). This data is also sent encrypted to [BackBlaze](https://www.backblaze.com/) as my offsite backup.

Because this server is for storage I decided it would be best to have a virtual machine running Plex and use TrueNAS Scale’s docker setup to run the various [Servarr](https://wiki.servarr.com) apps. The goal was to prevent unecessary network bandwidth being utilized for operations other than serving media to devices.

### NetApp DS4246
- 12x 3TB 7.2k SAS 3.5" HDD

There are twenty-four 3.5" HDD bays available but only twelve are currently active. This disk-shelf is connected to the R420 and is configured as a raidz1 pool with 2x vdevs consisting of 6x 3TB drives.

Why do I need so much storage? I don’t utilize it all right now but in planning my homelab I imagined setting up pull-through caches for package repositories that I use for day-to-day development such as Docker images, composer packages, and npm packages. I’m also interested in setting up [LAN Cache](https://lancache.net) for caching game downloads.

### Dell PowerEdge R620 (x2)
- 2x Intel Xeon E5-2650 v2 2.60 GHz (8 cores / 16 threads)
- 128 GB Memory (PC3-12800R)
- 2x 146 GB 15k SAS 2.5" HDD
- 6x 500 GB 7.2k SAS 2.5" HDD

These two servers are for primary compute and are setup as a two-node cluster using Proxmox. I run a number of [LXC containers](https://linuxcontainers.org) and a couple VMs running Docker for various services. Services like [MariaDB](https://mariadb.com), [Grafana](https://grafana.com), [Gitea](https://gitea.io/) with [Drone](https://www.drone.io), with many more to come. I run a handful of Minecraft servers for friends and family using [PaperMC](https://papermc.io), [Waterfall](https://github.com/PaperMC/Waterfall) for connecting all of them, and [GeyserMC](https://geysermc.org) to allow Java and Bedrock clients to connect. And finally I run a few instances of [CMaNGOS](https://cmangos.net) for World of Warcraft Vanilla, The Burning Crusade, and Wrath of the Lich King privately using the [Docker images I’ve published](https://github.com/jrtashjian/cmangos-docker).

There are future plans to add a third node to this cluster and maybe play around with Ceph and High-Availability built-in to Proxmox.

### Apple MacBook Pro (MacBookPro11,1)
- Intel Core i7-4558U 2.80 GHz (2 cores / 4 threads)
- 8 GB Memory

This old laptop is used for the occasional time I want or need access to Mac OS. I can (and have) virtualized Mac OS with Proxmox but it’s significantly less effort to just have a physical device ready to go.

### Not Pictured

I have a [TrippLite UPS](https://www.tripplite.com/support/SMART1500LCDT) that is not rack-mountable with the PDU plugged into it. I plan to purchase 2x 2U rack-mountable UPS’s in the future and place them at the bottom of the rack while splitting the load between them. I also pick up a new Microtik Managed Switch ([CSS326-24G-2S+RM](https://mikrotik.com/product/CSS326-24G-2SplusRM)) to replace the three [8-port Linksys SE3008](https://www.linksys.com/8-port-gigabit-ethernet-switch-se3008/SE3008.html) switches I’m currently using to separate subnets.

## Linode

I've been with [Linode](https://www.linode.com/) for as long as I've been developing professionally and I personally love the service and support they offer. Although I host things at home I have been self-hosting things on Linode and continue to. For instance I host a [Mastodon](https://joinmastodon.org/) instance for friends and family as well as a full-fledged email server. Linode provides a great interface for managing each server and allows me to easily update DNS records using their [DNS Manager](https://www.linode.com/docs/guides/dns-manager/). I even have [dynamic dns setup using Linode and OPNSense](https://docs.opnsense.org/manual/dynamic_dns.html).

## Conclusion

I love tinkering with my homelab and finding a balance between the complexity of hosting things and owning my data while trying to limit the maintenance burden of it all. If this kind of stuff is interesting to you take a look at the [introduction post on r/homelab](https://www.reddit.com/r/homelab/wiki/introduction/). You can get started using an old computer, your current computer using virtual machines, or a [Raspberry Pi](https://www.raspberrypi.org/). Be careful though! You may also end up with a server rack in your home.