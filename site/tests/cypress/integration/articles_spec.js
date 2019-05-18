describe('Article', function() {
  it('Visits an article', function() {
    // Visit the article
    cy.visitAndCheck('/articles/hello-world/')

    // Check background changes
    cy.get('body').should('have.css', 'background-color', 'rgb(255, 255, 255)')

    // Check title
    cy.get('h1').should('contain', 'Hello world!')
    // Check for categories
    cy.get('.single-header .categories a').should('contain', 'Back To Basics').should('contain', 'People')
    // Check date
    cy.get('time').should('contain', '13.05.19')
    // Check author
    cy.get('.author').should('contain', 'Sam Briggs')

    // Check suggestions
    cy.get('.suggestions')
      .should('contain', 'Sam Briggs')
      .should('contain', 'Back To Basics')
      .should('contain', 'People')
  })

  it('Visits a video article', function() {
    // Visit the article
    cy.visitAndCheck('/articles/my-first-video/')

    // Check the featured media is a video
    cy.get('.featured-image iframe').should('exist')

    // Check background changes
    cy.get('body').should('have.css', 'background-color', 'rgb(0, 0, 0)')
  })
})
