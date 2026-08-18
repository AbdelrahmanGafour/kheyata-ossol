/* بيانات المنتجات — متجر "للخياطة أصول" */

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
    name: 'مقص عينات كهربائي',
    category: 'cutting',
    price: 1850,
    oldPrice: 1950,
    image: 'assets/images/products/مقص عينات كهربائي.webp',
    images: ['assets/images/products/مقص عينات كهربائي.webp', 'assets/images/products/مقص عينات كهربائي 2.webp'],
    rating: 4.8,
    reviews: 34,
    badge: 'الأكثر مبيعًا',
    stock: 12,
    description: 'مقص عينات كهربائي عالي الكفاءة لقص الأقمشة والعينات بسرعة ودقة عالية، يوفر الوقت والمجهود في الورش والمصانع مقارنة بالقص اليدوي، وبناء متين يتحمل الاستخدام المكثف.'
  },
  {
    id: 'p02',
    name: 'مكبس كباسين يدوي',
    category: 'accessories',
    price: 350,
    oldPrice: 400,
    image: 'assets/images/products/مكبس كباسين يدوي.webp',
    rating: 4.5,
    reviews: 41,
    badge: 'خصم',
    stock: 25,
    description: 'مكبس يدوي لتركيب الكباسين والأزرار الضاغطة على الأقمشة بإحكام واحترافية، سهل الاستخدام ومناسب للورش والاستخدام المنزلي المتكرر.'
  },
  {
    id: 'p03',
    name: 'جهاز تني وتركيب استك اوتوماتيك مستورد',
    category: 'machines',
    price: 1850,
    oldPrice: 1950,
    image: 'assets/images/products/جهاز تني وتركيب استك اوتوماتيك مستورد.webp',
    rating: 4.9,
    reviews: 17,
    badge: 'مستورد',
    stock: 6,
    description: 'جهاز مستورد لتنّي وتركيب الأستك أوتوماتيكيًا بسرعة ودقة عالية، يقلل المجهود ووقت التصنيع مع نتائج ثابتة الجودة في كل قطعة.'
  },
  {
    id: 'p04',
    name: 'مقص pin الاحمر',
    category: 'cutting',
    price: 420,
    oldPrice: null,
    image: 'assets/images/products/مقص pin الاحمر.webp',
    rating: 4.7,
    reviews: 23,
    badge: 'مقاسات متعددة',
    stock: 40,
    description: 'مقص تفريز احترافي (Pin) يعطي حواف مسننة أنيقة ويمنع تنسل الأقمشة، متوفر بثلاثة مقاسات لتناسب كل احتياجات القص والتفصيل.',
    variations: {
      label: 'المقاس',
      options: [
        { label: 'مقاس 8', price: 420 },
        { label: 'مقاس 10', price: 460 },
        { label: 'مقاس 12', price: 520 }
      ]
    }
  },
  {
    id: 'p05',
    name: 'مسطره تحديد مقاس',
    category: 'measuring',
    price: 420,
    oldPrice: 470,
    image: 'assets/images/products/مسطره تحديد مقاس.webp',
    rating: 4.8,
    reviews: 52,
    badge: 'خصم',
    stock: 30,
    description: 'مسطرة معدنية دقيقة لتحديد المقاسات بسهولة واحترافية أثناء التفصيل والقص، من الأدوات الأساسية في كل ورشة خياطة.'
  },
  {
    id: 'p06',
    name: 'دكاكه حلزون',
    category: 'accessories',
    price: 550,
    oldPrice: 600,
    image: 'assets/images/products/دكاكه حلزون.webp',
    rating: 4.6,
    reviews: 15,
    badge: 'خصم',
    stock: 18,
    description: 'دكاكة حلزونية لعمل العراوي وتثبيت الكبسات بدقة وثبات، تصميم متين يدوم طويلًا مع الاستخدام المكثف في الورش.'
  },
  {
    id: 'p07',
    name: 'درج لمكينات الخياطه',
    category: 'accessories',
    price: 550,
    oldPrice: 600,
    image: 'assets/images/products/درج لمكينات الخياطه.webp',
    rating: 4.4,
    reviews: 19,
    badge: null,
    stock: 22,
    description: 'درج عملي لتنظيم أدوات الخياطة بجانب الماكينة، سهل التركيب ويوفر مساحة تخزين إضافية للإبر والخيوط والملحقات.'
  },
  {
    id: 'p08',
    name: 'علبة مسطرة تني الأورلية',
    category: 'measuring',
    price: 450,
    oldPrice: 550,
    image: 'assets/images/products/علبة مسطرة تني الأورلية.webp',
    rating: 4.7,
    reviews: 12,
    badge: 'خصم',
    stock: 14,
    description: 'علبة مع مسطرة مخصصة لضبط تنّي الأورلية بدقة، تحافظ على الأداة من التلف وتسهّل استخدامها أثناء العمل.'
  },
  {
    id: 'p09',
    name: 'دواسه تني متحركك',
    category: 'accessories',
    price: 360,
    oldPrice: 400,
    image: 'assets/images/products/دواسه تني متحركك.webp',
    rating: 4.5,
    reviews: 21,
    badge: 'خصم',
    stock: 27,
    description: 'دواسة متحركة لتنّي الأستك أثناء التفصيل، تمنح تحكمًا أفضل وسرعة أعلى في الإنتاج مقارنة بالطرق التقليدية.'
  },
  {
    id: 'p10',
    name: 'متر قياس إلكتروني',
    category: 'measuring',
    price: 650,
    oldPrice: 700,
    image: 'assets/images/products/متر قياس إلكتروني.webp',
    rating: 4.6,
    reviews: 29,
    badge: 'الأكثر طلبًا',
    stock: 20,
    description: 'متر قياس إلكتروني رقمي يعرض القياسات بدقة وسرعة، بديل عملي وموفّر للوقت عن المتر التقليدي في أعمال التفصيل والقص.'
  },
];

function getProductById(id) {
  return window.PRODUCTS.find(function (p) { return p.id === id; });
}
function getCategoryById(id) {
  return window.CATEGORIES.find(function (c) { return c.id === id; });
}

/* يبحث عن خيار تنويع بعينه ضمن منتج (يُستخدم عند حساب سعر عنصر في السلة) */
function getVariationOption(product, optionLabel) {
  if (!product || !product.variations) return null;
  return product.variations.options.find(function (o) { return o.label === optionLabel; }) || null;
}
