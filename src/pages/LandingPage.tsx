import React from 'react';
import { Navbar } from '@/components/layout/Navbar';
import { HeroSection } from '@/components/home/HeroSection';
import { FeatureBar } from '@/components/home/FeatureBar';
import { CategorySection } from '@/components/home/CategorySection';
import { ProductsSection } from '@/components/home/ProductsSection';
import { SellersSection } from '@/components/home/SellersSection';
import { CreatorBanner } from '@/components/home/CreatorBanner';
import { StepsSection } from '@/components/home/StepsSection';
import { NewsletterSection } from '@/components/home/NewsletterSection';
import { Footer } from '@/components/layout/Footer';

interface LandingPageProps {
  onNavigateShop?: () => void;
  onNavigateSeller?: (id?: string) => void;
}

export const LandingPage: React.FC<LandingPageProps> = ({ onNavigateShop, onNavigateSeller }) => {
  return (
    <div className="min-h-screen flex flex-col justify-between selection:bg-brand-purple selection:text-white bg-[#FAF9FC] text-[#111827]">
      <Navbar
        activeTab="beranda"
        onTabChange={(tab) => {
          if (tab === 'belanja') onNavigateShop?.();
        }}
      />
      <HeroSection />
      <FeatureBar />
      <ProductsSection />
      <SellersSection />
      <CreatorBanner />
      <StepsSection />
      <NewsletterSection />
      <Footer />
    </div>
  );
};
