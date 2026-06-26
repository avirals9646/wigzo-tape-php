import React from "react";

const policies = [
  {
    id: "privacy",
    icon: "🛡️",
    title: "Privacy Policy",
    content: `This Privacy Policy explains how Wigzo Tapes collects, uses, and protects customer information.
We may collect your name, email address, phone number, billing address, shipping address, and payment-related information required for order fulfillment. Information may also be collected automatically through cookies and website analytics tools.

Customer information is used for processing orders, providing customer support, improving website functionality, sending order updates, and complying with legal obligations. We do not sell customer information to third parties.

Information may be shared only with trusted payment processors, shipping partners, and service providers required to complete transactions. We take reasonable security measures to protect personal information; however, no online transmission method can be guaranteed to be completely secure.

By using our website, you consent to the practices described in this Privacy Policy.`,
  },
  {
    id: "terms",
    icon: "📜",
    title: "Terms & Conditions",
    content: `By accessing or using WigzoTapes.com, you agree to be bound by these Terms & Conditions. All products displayed on the website are subject to availability. Prices, descriptions, and specifications may be modified without prior notice.

Wigzo Tapes reserves the right to accept, reject, or cancel any order at its sole discretion, including cases involving suspected fraud, incorrect pricing, or stock unavailability. Customers are responsible for providing accurate billing and shipping information.

All website content, including logos, images, text, graphics, and product descriptions, is the intellectual property of Wigzo Tapes and may not be copied, reproduced, or distributed without written permission.

These Terms & Conditions shall be governed by the laws of India, and any disputes shall be subject to the jurisdiction of courts located in Delhi.`,
  },
  {
    id: "refund",
    icon: "💳",
    title: "Refund & Cancellation Policy",
    content: `Customers may request cancellation within 12 hours of placing an order. Once an order has been shipped, cancellation requests cannot be accepted.

Due to the hygienic nature of wig tapes and related beauty products, opened, used, or damaged products are not eligible for return or exchange.

If a customer receives an incorrect, defective, or damaged product, they must notify Wigzo Tapes within 48 hours of delivery and provide supporting photographs or videos.

After verification and approval, refunds will be processed to the original payment method within 5–7 business days.

Refunds will not be provided for change-of-mind purchases, incorrect address information supplied by the customer, or refusal to accept delivery.`,
  },
  {
    id: "shipping",
    icon: "🚚",
    title: "Shipping Policy",
    content: `Orders are typically processed within 1–2 business days after payment confirmation. Processing times may be longer during peak sales periods, holidays, or unforeseen circumstances.

Estimated delivery times:
• Metro Cities: 2–5 business days
• Other Locations: 4–8 business days
• Remote Areas: 5–10 business days

Customers will receive tracking information after their order has been dispatched. Delivery timelines are estimates and may vary due to courier delays, weather conditions, or operational issues beyond our control.

Customers are responsible for providing accurate shipping information. Additional shipping charges may apply if re-delivery is required due to an incorrect address.`,
  },
];

export default function LegalPolicies() {
  return (
    <div className="min-h-screen bg-[#f8fafc]">
      <section className="bg-gradient-to-br from-[#17847c] to-[#0f5f59] text-white py-20 px-6">
        <div className="max-w-6xl mx-auto text-center">
          <p className="uppercase tracking-[0.35em] text-sm text-white/80 mb-4">
            Wigzo Tapes
          </p>
          <h1 className="text-4xl md:text-6xl font-bold mb-6">
            Legal Policies
          </h1>
          <p className="max-w-3xl mx-auto text-lg md:text-xl text-white/90">
            Your privacy, secure payments, transparent shipping, and customer
            satisfaction are our highest priorities.
          </p>
        </div>
      </section>

      <section className="max-w-6xl mx-auto px-6 py-10">
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {policies.map((item) => (
            <a
              key={item.id}
              href={`#${item.id}`}
              className="bg-white rounded-2xl p-5 text-center shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition"
            >
              <div className="text-3xl mb-2">{item.icon}</div>
              <p className="font-semibold text-gray-800">{item.title}</p>
            </a>
          ))}
        </div>
      </section>

      <main className="max-w-6xl mx-auto px-6 pb-20">
        <div className="grid lg:grid-cols-[260px_1fr] gap-8">
          <aside className="hidden lg:block">
            <div className="sticky top-24 bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
              <h3 className="font-bold text-gray-900 mb-4">Quick Links</h3>
              <nav className="space-y-3">
                {policies.map((item) => (
                  <a
                    key={item.id}
                    href={`#${item.id}`}
                    className="block text-gray-600 hover:text-[#17847c] font-medium"
                  >
                    {item.icon} {item.title}
                  </a>
                ))}
                <a
                  href="#contact"
                  className="block text-gray-600 hover:text-[#17847c] font-medium"
                >
                  📞 Contact Information
                </a>
              </nav>
            </div>
          </aside>

          <div className="space-y-8">
            {policies.map((item) => (
              <section
                key={item.id}
                id={item.id}
                className="bg-white rounded-3xl p-7 md:p-10 shadow-sm border border-gray-100 scroll-mt-28"
              >
                <div className="flex items-center gap-4 mb-5">
                  <div className="w-14 h-14 rounded-2xl bg-[#17847c]/10 flex items-center justify-center text-3xl">
                    {item.icon}
                  </div>
                  <h2 className="text-2xl md:text-3xl font-bold text-gray-900">
                    {item.title}
                  </h2>
                </div>

                <div className="text-gray-700 leading-8 whitespace-pre-line text-[15.5px]">
                  {item.content}
                </div>
              </section>
            ))}

            <section
              id="contact"
              className="bg-[#10201f] text-white rounded-3xl p-7 md:p-10 shadow-sm scroll-mt-28"
            >
              <h2 className="text-2xl md:text-3xl font-bold mb-6">
                Contact Information
              </h2>

              <div className="grid md:grid-cols-3 gap-5">
                <div className="bg-white/10 rounded-2xl p-5">
                  <p className="text-white/70 mb-1">Business Name</p>
                  <p className="font-semibold">Wigzo Tapes</p>
                </div>

                <div className="bg-white/10 rounded-2xl p-5">
                  <p className="text-white/70 mb-1">Email</p>
                  <p className="font-semibold break-all">
                    wigzotapes@gmail.com
                  </p>
                </div>

                <div className="bg-white/10 rounded-2xl p-5">
                  <p className="text-white/70 mb-1">Phone</p>
                  <p className="font-semibold">+91 7217693925</p>
                </div>
              </div>

              <div className="bg-white/10 rounded-2xl p-5 mt-5">
                <p className="text-white/70 mb-1">Address</p>
                <p className="font-semibold">
                  H 38, Street No. 4, Brahmpuri, Delhi - 110053
                </p>
              </div>
            </section>

            <p className="text-center text-gray-500 text-sm">
              Last Updated: June 2026 © Wigzo Tapes. All Rights Reserved.
            </p>
          </div>
        </div>
      </main>
    </div>
  );
}