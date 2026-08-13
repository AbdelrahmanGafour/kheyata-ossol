/* بيانات المنتجات — تجريبية لمتجر "للخياطة أصول" */

window.CATEGORIES = [
  { id: 'machines', name: 'مكن الخياطة', icon: 'fa-solid fa-gear' },
  { id: 'cutting', name: 'أدوات القص', icon: 'fa-solid fa-scissors' },
  { id: 'measuring', name: 'أدوات القياس', icon: 'fa-solid fa-ruler-combined' },
  { id: 'thread', name: 'خيوط وإبر', icon: 'fa-solid fa-thumbtack' },
  { id: 'fabric', name: 'أقمشة', icon: 'fa-solid fa-shirt' },
  { id: 'accessories', name: 'إكسسوارات', icon: 'fa-solid fa-toolbox' },
];

window.PRODUCTS = [
  {
    id: 'p01',
    name: 'ماكينة خياطة منزلية احترافية',
    category: 'machines',
    price: 4200,
    oldPrice: 4800,
    image: 'assets/images/products/product-sewing-machine.jpg',
    rating: 4.8,
    reviews: 62,
    badge: 'الأكثر مبيعًا',
    stock: 14,
    description: 'ماكينة خياطة منزلية أصلية بمحرك قوي وهادئ، مناسبة للمبتدئين والمحترفين، تدعم أكثر من 20 نوع غرزة مع ضمان قطع غيار.'
  },
  {
    id: 'p02',
    name: 'ماكينة خياطة صناعية',
    category: 'machines',
    price: 8900,
    oldPrice: null,
    image: 'assets/images/products/product-industrial-machine.jpg',
    rating: 4.9,
    reviews: 31,
    badge: 'أداء عالي',
    stock: 6,
    description: 'ماكينة صناعية مخصصة للورش وخطوط الإنتاج، سرعة تشغيل عالية وثبات ممتاز في الغرز على الأقمشة السميكة.'
  },
  {
    id: 'p03',
    name: 'ماكينة خياطة تراثية نادرة',
    category: 'machines',
    price: 6500,
    oldPrice: 7200,
    image: 'assets/images/hero/hero-vintage.jpg',
    rating: 5,
    reviews: 18,
    badge: 'نادرة',
    stock: 3,
    description: 'قطعة تراثية أصلية من نوادر ماكينات الخياطة، تحفة فنية تجمع بين الجمال الكلاسيكي وكفاءة التشغيل.'
  },
  {
    id: 'p04',
    name: 'ماكينة خياطة متنقلة صغيرة',
    category: 'machines',
    price: 1450,
    oldPrice: null,
    image: 'assets/images/tailor-machine-2.jpg',
    rating: 4.5,
    reviews: 44,
    badge: null,
    stock: 22,
    description: 'ماكينة خفيفة الوزن سهلة الحمل، مثالية للتدريب المنزلي والإصلاحات السريعة والمشاريع الصغيرة.'
  },
  {
    id: 'p05',
    name: 'مقص قماش احترافي ستانلس',
    category: 'cutting',
    price: 350,
    oldPrice: 420,
    image: 'assets/images/products/product-scissors.jpg',
    rating: 4.7,
    reviews: 88,
    badge: 'خصم',
    stock: 40,
    description: 'مقص خياطة مصنوع من الستانلس ستيل الفاخر، حواف حادة تدوم طويلًا مع قبضة مريحة للاستخدام الممتد.'
  },
  {
    id: 'p06',
    name: 'عجلة قص دوارة Rotary Cutter',
    category: 'cutting',
    price: 280,
    oldPrice: null,
    image: 'assets/images/products/product-fabric-cutting.jpg',
    rating: 4.6,
    reviews: 27,
    badge: 'جديد',
    stock: 30,
    description: 'أداة قص دوارة دقيقة لتقطيع طبقات القماش المتعددة بخط مستقيم ونظيف، مثالية لهواة الباتشورك.'
  },
  {
    id: 'p07',
    name: 'مسطرة تحديد مقاس عراوي وزراير',
    category: 'measuring',
    price: 420,
    oldPrice: null,
    image: 'assets/images/about/about-workshop.jpg',
    rating: 4.9,
    reviews: 53,
    badge: 'الأكثر طلبًا',
    stock: 25,
    description: 'مسطرة معدنية دقيقة لتحديد أماكن العراوي والأزرار بسهولة واحترافية، من أدوات الأصول الأساسية في كل ورشة.'
  },
  {
    id: 'p08',
    name: 'شريط قياس احترافي مزدوج',
    category: 'measuring',
    price: 65,
    oldPrice: null,
    image: 'assets/images/about/measuring-waist.jpg',
    rating: 4.4,
    reviews: 71,
    badge: null,
    stock: 120,
    description: 'شريط قياس مرن بمقاسين (سم وبوصة)، لا يتمدد ولا يتقطع، أساسي لكل خياط ومصمم أزياء.'
  },
  {
    id: 'p09',
    name: 'طقم إبر خياطة متنوعة',
    category: 'thread',
    price: 90,
    oldPrice: null,
    image: 'assets/images/products/product-needle-closeup.jpg',
    rating: 4.3,
    reviews: 39,
    badge: null,
    stock: 90,
    description: 'مجموعة إبر بمقاسات مختلفة تناسب كافة أنواع الأقمشة من الحرير الرقيق إلى الجينز السميك.'
  },
  {
    id: 'p10',
    name: 'بكر خيوط ملونة (طقم 40 لون)',
    category: 'thread',
    price: 220,
    oldPrice: 260,
    image: 'assets/images/thread-spools-wall.jpg',
    rating: 4.8,
    reviews: 65,
    badge: 'خصم',
    stock: 35,
    description: 'طقم بكر خيوط بولي إستر عالي الجودة بـ40 درجة لون، مقاوم للتلف والبهتان.'
  },
  {
    id: 'p11',
    name: 'بكر خيوط حرير فاخر',
    category: 'thread',
    price: 260,
    oldPrice: null,
    image: 'assets/images/thread-spools-2.jpg',
    rating: 4.6,
    reviews: 21,
    badge: null,
    stock: 18,
    description: 'خيوط حريرية فاخرة بلمعان مميز، مناسبة للتطريز والقطع الراقية والفساتين.'
  },
  {
    id: 'p12',
    name: 'طقم تطريز متكامل',
    category: 'thread',
    price: 310,
    oldPrice: null,
    image: 'assets/images/thread-scissors-embroidery.jpg',
    rating: 4.7,
    reviews: 16,
    badge: 'جديد',
    stock: 20,
    description: 'طقم تطريز يحتوي على خيوط ملونة ومقص صغير دقيق وإبر تطريز مخصصة لهواة الأعمال اليدوية.'
  },
  {
    id: 'p13',
    name: 'قماش قطني أبيض عالي الجودة (المتر)',
    category: 'fabric',
    price: 85,
    oldPrice: null,
    image: 'assets/images/cutting-cloth.jpg',
    rating: 4.5,
    reviews: 33,
    badge: null,
    stock: 200,
    description: 'قماش قطني 100% بجودة تصدير، ناعم الملمس ومناسب لجميع أنواع التفصيل والتطريز.'
  },
  {
    id: 'p14',
    name: 'طقم أدوات خياطة متكامل',
    category: 'accessories',
    price: 480,
    oldPrice: 550,
    image: 'assets/images/sewing-supplies-flatlay.jpg',
    rating: 4.9,
    reviews: 47,
    badge: 'باقة متكاملة',
    stock: 15,
    description: 'كل ما يحتاجه المبتدئ في مكان واحد: إبر، دبابيس، مقص صغير، شريط قياس، وخيوط أساسية داخل حقيبة أنيقة.'
  },
  {
    id: 'p15',
    name: 'قدم ضاغطة متعددة الاستخدامات',
    category: 'accessories',
    price: 150,
    oldPrice: null,
    image: 'assets/images/products/product-presser-foot.jpg',
    rating: 4.4,
    reviews: 12,
    badge: null,
    stock: 50,
    description: 'قدم ضاغطة قابلة للتركيب على أغلب ماكينات الخياطة المنزلية، تسهّل غرز السحاب والحواف والزراير.'
  },
];

function getProductById(id) {
  return window.PRODUCTS.find(function (p) { return p.id === id; });
}
function getCategoryById(id) {
  return window.CATEGORIES.find(function (c) { return c.id === id; });
}
