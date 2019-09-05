# Youtube-Video-Fetcher
A Wordpress Plugin for video category site who wants their contents added from youtube
/*------------------------HOW TO USE-------------------------------------------------*/
1.Create 4 Page with slug home,category,search,watch
2.Install the plugin
3.Update Plugin Setting from "Setting" Submenu ( API key and Watch size) Leave default video for now
4.Create Required Categories from "Add New Category" menu
5.Now This is time to fetch Some Videos
  -->Here You can fetch either a single video or whole playlist
For fetching of a single video,You will need to put the value of video id,suppose url of video is 
Suppose url of youtube video is: https://www.youtube.com/watch/?v=gheVSx-U2_0 
then video id is :gheVSx-U2_0(value of 'v' parameter)
Suppose url of playlist is:https://www.youtube.com/watch?v=JGwWNGJdvx8&list=PLMC9KNkIncKtPzgY-5rmhvj7fax8fdxoj
then Playlist id is:PLMC9KNkIncKtPzgY-5rmhvj7fax8fdxoj(value of 'list' parameter)
For Homepage(A page with slug /home):[yvf_home]
For Category Page(A page with slug /category):[yvf_category]
For Search Page (A page with slug /search):[yvf_search]
For Watch Page (A page with slug /watch) : [yvf_watch]
