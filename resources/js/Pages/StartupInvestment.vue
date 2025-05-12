<template>
    <Head title="Investir avec BrainCode" />
    <AppHeader :canLogin="true" :canRegister="true" />
    <div class="investment-page">
      <!-- Hero Section with improved layout and visual hierarchy -->
      <section class="hero bg-gradient-to-r from-blue-50 to-white py-20">
      <div class="container mx-auto px-4 md:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="hero-content max-w-xl md:pr-6">
          <h1 class="text-4xl md:text-5xl font-bold text-blue-600 mb-4">Investissez dans les startups de demain</h1>
          <p class="text-gray-700 text-lg mb-8">Explorez l'innovation, accompagnez des entrepreneurs passionnés et bénéficiez d'une croissance durable avec BrainCode startups.</p>
          <div class="flex space-x-4">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-base font-medium transition duration-300 shadow-md hover:shadow-lg">Rejoindre maintenant</button>
            <button class="border border-blue-600 text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg text-base font-medium transition duration-300">En savoir plus</button>
          </div>
        </div>
        <div class="hero-image mt-8 md:mt-0 md:ml-6">
          <img src="/image/Investisseur/aa.png" alt="Personne debout sur une pièce d'or" class="h-96 mx-auto drop-shadow-lg" />
        </div>
      </div>
    </section>
  
      <!-- Stats Section (New) -->
      <section class="bg-white py-12">
        <div class="container mx-auto px-4 md:px-8">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center p-6 rounded-lg hover:shadow-md transition duration-300">
              <p class="text-4xl font-bold text-blue-600 mb-2">150+</p>
              <p class="text-gray-600">Startups accompagnées</p>
            </div>
            <div class="text-center p-6 rounded-lg hover:shadow-md transition duration-300">
              <p class="text-4xl font-bold text-blue-600 mb-2">€25M</p>
              <p class="text-gray-600">Investis au total</p>
            </div>
            <div class="text-center p-6 rounded-lg hover:shadow-md transition duration-300">
              <p class="text-4xl font-bold text-blue-600 mb-2">92%</p>
              <p class="text-gray-600">Taux de réussite</p>
            </div>
            <div class="text-center p-6 rounded-lg hover:shadow-md transition duration-300">
              <p class="text-4xl font-bold text-blue-600 mb-2">400+</p>
              <p class="text-gray-600">Investisseurs actifs</p>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Why Invest Section with improved visuals -->
      <section class="why-invest bg-blue-50 py-20">
        <div class="container mx-auto px-4 md:px-8">
          <h2 class="text-3xl font-bold text-blue-600 mb-12 text-center">Pourquoi investir avec BrainCode ?</h2>
          <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="investment-graphic mb-12 md:mb-0 md:w-2/5">
              <img src="/image/Investisseur/aaa.png" alt="Pièces empilées et graphique croissant" class="h-96 mx-auto drop-shadow-lg" />
            </div>
            
            <div class="investment-reasons md:w-1/2">
              <div class="grid grid-cols-1 gap-6">
                <div class="flex items-start p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition duration-300">
                  <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </div>
                  </div>
                  <div>
                    <h3 class="font-semibold text-lg mb-1">Accès à des startups qualifiées</h3>
                    <p class="text-gray-600">Nous sélectionnons rigoureusement les startups les plus prometteuses pour vous garantir des opportunités de qualité.</p>
                  </div>
                </div>
                
                <div class="flex items-start p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition duration-300">
                  <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                      </svg>
                    </div>
                  </div>
                  <div>
                    <h3 class="font-semibold text-lg mb-1">Opportunités diversifiées</h3>
                    <p class="text-gray-600">Un large éventail de secteurs d'activité pour diversifier votre portefeuille d'investissements.</p>
                  </div>
                </div>
                
                <div class="flex items-start p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition duration-300">
                  <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                      </svg>
                    </div>
                  </div>
                  <div>
                    <h3 class="font-semibold text-lg mb-1">Sécurisation de l'investissement</h3>
                    <p class="text-gray-600">Nous mettons en place des mécanismes de protection pour minimiser les risques liés à vos investissements.</p>
                  </div>
                </div>
                
                <div class="flex items-start p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition duration-300">
                  <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                      </svg>
                    </div>
                  </div>
                  <div>
                    <h3 class="font-semibold text-lg mb-1">Rentabilité et scalabilité</h3>
                    <p class="text-gray-600">Nous ciblons des startups à fort potentiel de croissance pour maximiser votre retour sur investissement.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Main Heading Section -->
      <section class="investor-features-heading bg-white pt-20 pb-6">
        <div class="container mx-auto px-4 md:px-8">
          <h2 class="text-3xl font-bold text-blue-600 text-center mb-4">Fonctionnalités dédiées aux investisseurs</h2>
          <p class="text-gray-600 text-center max-w-2xl mx-auto">Découvrez nos outils spécialement conçus pour optimiser votre expérience d'investissement et suivre vos opportunités.</p>
        </div>
      </section>
  
      <!-- Features Section (redesigned) -->
      <section class="features-section bg-white py-12">
        <div class="container mx-auto px-4 md:px-8">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
              <div class="p-4 bg-blue-50">
                <img src="/image/Investisseur/aaaa.png" alt="Icône de messagerie" class="h-32 mx-auto" />
              </div>
              <div class="p-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">Messagerie intégrée avec les startups</h3>
                <ul class="space-y-3 text-gray-600">
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Communication directe avec les startups</span>
                  </li>
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Échange sécurisé de documents (NDA, Business Plan)</span>
                  </li>
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Historique des conversations</span>
                  </li>
                </ul>
              </div>
            </div>
  
            <!-- Feature 2 -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
              <div class="p-4 bg-blue-50">
                <img src="/image/Investisseur/b.png" alt="Icône de bouclier avec pièce" class="h-32 mx-auto" />
              </div>
              <div class="p-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">Gestion des demandes d'investissement</h3>
                <ul class="space-y-3 text-gray-600">
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Réception des demandes des startups</span>
                  </li>
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Envoi de propositions personnalisées</span>
                  </li>
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Suivi des négociations en cours</span>
                  </li>
                </ul>
              </div>
            </div>
  
            <!-- Feature 3 -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
              <div class="p-4 bg-blue-50">
                <img src="/image/Investisseur/bb.png" alt="Graphique rond avec pièces" class="h-32 mx-auto" />
              </div>
              <div class="p-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">Suivi des startups en portefeuille</h3>
                <ul class="space-y-3 text-gray-600">
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Tableau de bord des startups</span>
                  </li>
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Suivi des KPIs en temps réel</span>
                  </li>
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Rapports d'évolution périodiques</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Testimonials Section (New) -->
      <section class="bg-blue-50 py-16">
        <div class="container mx-auto px-4 md:px-8">
          <h2 class="text-3xl font-bold text-blue-600 text-center mb-12">Ce que disent nos investisseurs</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg p-6 shadow-md">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                  <span class="text-blue-600 font-bold">MB</span>
                </div>
                <div>
                  <h4 class="font-medium">Michel Bouvier</h4>
                  <p class="text-gray-500 text-sm">Business Angel</p>
                </div>
              </div>
              <p class="text-gray-600 italic">"BrainCode m'a permis d'accéder à des startups innovantes qui correspondent parfaitement à ma stratégie d'investissement. Le processus est fluide et transparent."</p>
              <div class="flex mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
            </div>
            
            <div class="bg-white rounded-lg p-6 shadow-md">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                  <span class="text-blue-600 font-bold">SL</span>
                </div>
                <div>
                  <h4 class="font-medium">Sophie Laurent</h4>
                  <p class="text-gray-500 text-sm">Fonds d'investissement</p>
                </div>
              </div>
              <p class="text-gray-600 italic">"La qualité des startups proposées et les outils de suivi mis à disposition font de BrainCode une plateforme incontournable pour tout investisseur sérieux."</p>
              <div class="flex mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="py-16 bg-white ">
      <div class="max-w-5xl mx-auto px-6 text-center">
        <div class="flex items-center justify-center mb-4">
          <h2 class="text-3xl font-bold text-blue-600 text-center mb-4">
            Rejoignez notre réseau d’investisseurs !
          </h2>
          <img 
            src="/image/startup/rocket-icon.png" 
            alt="Rocket icon" 
            class="w-10 h-10 ml-3"
          />
        </div>
        
        <p class="text-lg text-gray-700  max-w-3xl mx-auto mb-8">
            Prêt à investir dans des startups prometteuses et à accompagner l’innovation ? Rejoignez-nous dès maintenant pour découvrir nos opportunités d’investissement.
        </p>
        
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition hover:shadow-lg">
            Postuler en tant que investisseur
        </button>
      </div>
    </section>
  </div>
  <Footer />
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import { defineAsyncComponent, ref, onMounted } from 'vue';
import Footer from "@/Components/Footer.vue";

const AppHeader = defineAsyncComponent(() => 
  import("@/Components/Welcome/AppHeader.vue")
);

export default {
  name: 'InvestmentLandingPage',
  components: {
    Head,
    AppHeader,
    Footer
  },
  setup() {
    // Reactive data for potential future functionality
    const isModalOpen = ref(false);
    
    // Could add more reactive data and methods as needed
    
    return {
      isModalOpen
    };
  }
}
</script>

<style scoped>
.investment-page {
  font-family: 'Segoe UI', 'Roboto', sans-serif;
  color: #333;
  scroll-behavior: smooth;
}

.investment-page .container {
  width: 100%;
  max-width: 1280px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 1rem;
  padding-right: 1rem;
}

@media (min-width: 640px) {
  .investment-page .container {
    padding-left: 2rem;
    padding-right: 2rem;
  }
}

@media (min-width: 1024px) {
  .investment-page .container {
    padding-left: 4rem;
    padding-right: 4rem;
  }
}

/* Animation for hover effects */
@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
  }
  70% {
    box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
  }
}

.hero button:hover {
  animation: pulse 1.5s infinite;
}

/* Smooth transitions */
.transition {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}
</style>