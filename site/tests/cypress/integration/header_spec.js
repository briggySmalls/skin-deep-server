describe('Header', function() {
  describe('Highlight selected', function() {
    it('Home', function() {
      // Visit the home page
      cy.visit('/')

      // Check that 'Articles' is highlighted and the others are not
      cy.get('#menu-main-menu > li.menu-articles').should('have.class', 'active')
      cy.get('#menu-main-menu > li:not(.menu-articles)').should('not.have.class', 'active')
      cy.get('#menu-main-menu-1 > li').should('exist.and.not.have.class', 'active')
    })

    it('Events', function() {
      // Visit the home page
      cy.visit('/events')

      // Check that 'Articles' is highlighted and the others are not
      cy.get('#menu-main-menu > li.menu-events').should('have.class', 'active')
      cy.get('#menu-main-menu > li:not(.menu-events)').should('not.have.class', 'active')
      cy.get('#menu-main-menu-1').should('not.exist')
    })

    it('Shop', function() {
      // Visit the home page
      cy.visit('/products')

      // Check that 'Articles' is highlighted and the others are not
      cy.get('#menu-main-menu > li.menu-shop').should('have.class', 'active')
      cy.get('#menu-main-menu > li:not(.menu-shop)').should('not.have.class', 'active')
      cy.get('#menu-main-menu-1').should('not.exist')
    })

    it('Back To Basics', function() {
      // Visit the home page
      cy.visit('/articles/category/back-to-basics')

      // Check that 'Articles' is highlighted and the others are not
      cy.get('#menu-main-menu > li.menu-articles').should('have.class', 'active')
      cy.get('#menu-main-menu > li:not(.menu-articles)').should('not.have.class', 'active')
      cy.get('#menu-main-menu-1').should('exist')
      cy.get('#menu-main-menu-1 > li.menu-back-to-basics').should('have.class', 'active')
      cy.get('#menu-main-menu-1 > li:not(.menu-back-to-basics').should('not.have.class', 'active')
    })
  })
})
