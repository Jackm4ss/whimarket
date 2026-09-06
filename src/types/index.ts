export interface Category {
  id: string;
  name: string;
  image: string;
  href: string;
  bgColor: string;
}

export interface Creator {
  id: string;
  name: string;
  handle: string;
  role: string;
  avatar: string;
  verified: boolean;
  rating: number;
  itemCount: number;
  salesCount: number;
}

export interface Product {
  id: string;
  title: string;
  sellerName: string;
  sellerAvatar: string;
  verified: boolean;
  priceText: string;
  priceNumber: number;
  likes: number;
  image: string;
  href: string;
}

export interface StepItem {
  stepNumber: number;
  title: string;
  description: string;
  image: string;
}
