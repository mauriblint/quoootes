var FontsData = [
	// { name: 'Kredit', custom: true, },
	// { name: 'Team Spirit', custom: true, },
	// { name: 'Rubik Mono One', },
	{ name: 'Simplicity', custom: true, },
    { name: 'Courier', custom: true, },
	{ name: 'Frenchpress', custom: true, },
	{ name: 'Cocogoose', custom: true, },
	{ name: 'The Bold Font', custom: true, },
	{ name: 'Source Serif Pro' },
	{ name: 'Brook', custom: true, },
	{ name: 'Luckiest Guy', },
	{ name: 'Fredoka One', },
	{ name: 'Gochi Hand', },
    { name: 'Type Report', custom: true, },
    { name: 'Sifonn Outline', custom: true, },
	{ name: 'Boogaloo', },
	{ name: 'Barrio', },
	{ name: 'Caveat', weight: '700', },
	{ name: 'Londrina Solid', },
	{ name: 'Cutive Mono', },
	{ name: 'Shizuru', },
	{ name: 'Open Sans', weight: '400;700', },
	{ name: 'Roboto', },
	{ name: 'Erica One', },
	{ name: 'Special Elite',},
	{ name: 'Sigmar One',},
	{ name: 'Skranji',},
	{ name: 'Bevan',},
	{ name: 'Indie Flower',},
	{ name: 'Bungee Outline'},
	{ name: 'Londrina Outline'},
	{ name: 'Amatic SC',},
	{ name: 'Homemade Apple',},
	{ name: 'Dancing Script',},
	{ name: 'Annie Use Your Telescope',},
	{
		name: 'Merriweather Sans',
		weight: '800',
	},
	{
		name: 'Montserrat',
		weight: '700;900',
	},
	// {
	// 	title: 'Montserrat Bold',
    //     name: 'Montserrat',
	// 	weight: '900',
	// },
	{ name: 'Coming Soon',},
	{
		name: 'Lato',
		weight: '400;700',
	},
	{
		name: 'Source Sans Pro',
		weight: '700',
	},
	{
		name: 'Arvo',
		weight: '400;700',
	},
	{
		name: 'PT Sans',
		weight: '400;700',
	},
	{
		name: 'Work Sans',
		weight: '400;800',
	},
	{
		name: 'Patrick Hand',
	},
	{
		name: 'Itim',
	},
	{
		name: 'Rhodium Libre',
	},
	{
		name: 'Permanent Marker',
	},
	{
		name: 'Architects Daughter',
	},
	{
		name: 'Bree Serif',
	},
	{
		name: 'Didact Gothic',
	},
	{
		name: 'Lobster',
	},
	{
		name: 'Anton',
	},
	{
		name: 'Shrikhand',
	},
	{
		name: 'Faster One',
	},
	{
		name: 'Chelsea Market',
	},
	{
		name: 'Acme',
	},
	// {
	// 	name: 'Montserrat',
	// },
	{
		name: 'IM Fell English SC',
	},
	{
		name: 'Londrina',
	},
	{
		name: 'Cormorant SC',
	},
];

var FiltersData = [
    {name: 'No Filter', color: ''},
    {name: 'Retro', color: 'linear-gradient(to left, #FAACA8 0%, #DDD6F3 100%)'},
    {name: 'Blue Sky', color: 'linear-gradient(to left, #1CB5E0, #000046)'},
    {name: 'Sunset', color: 'linear-gradient(to left, #F0CB35, #C02425)'},
    {name: 'Magenta', color: 'linear-gradient(to left, #333399, #ff00cc)'},
    // {name: 'FilterName', color: 'linear-gradient(to left, #48b1bf, #06beb6)'},
    {name: 'Fun', color: 'linear-gradient(to left, #db36a4, #f7ff00)'},
    {name: 'Hope', color: 'linear-gradient(to left, #a8e063, #56ab2f)'},
    {name: 'Salmon', color: 'linear-gradient(to left, #ff9068, #fd746c)'},
    {name: 'Ocean', color: 'linear-gradient(to left, #0072ff, #00c6ff)'},
    {name: 'Tokio', color: 'linear-gradient(to left, #904e95, #e96443)'},
    {name: 'Cool', color: 'linear-gradient(to left, #00dbde, #fc00ff)'},
    {name: 'Mexico', color: 'linear-gradient(to left, #C6426E, #642B73)'},
    {name: 'Aqua', color: 'linear-gradient(to left, #78ffd6, #007991)'},
    {name: 'Antigua', color: 'linear-gradient(to left, #20002c, #cbb4d4)'},
    {name: 'Sepia', color: 'linear-gradient(to left, #c7bc84, #9e9853)'},
];

FontsData = FontsData.sort(function(a, b){
	return (a.name < b.name) ? -1 : 1;
});