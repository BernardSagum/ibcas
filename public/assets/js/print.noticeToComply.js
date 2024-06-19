function generatePDFPrintNotice(OrSeries, blpdNUm, dateIssued, BusinessName, BusinessAddress) {
  // Define the document 
  var docDefinition = {
      content: [
          { text: 'Control No.: ' + OrSeries, style: 'headerright' },
          { text: 'Republic of the Philippines', style: 'header' },
          { text: 'Province of Pampanga', style: 'header' },
          { text: 'CITY OF SAN FERNANDO', style: 'header' },
          { text: 'OFFICE OF THE CITY TREASURER', style: 'header3' },
          '\n\n',
          { text: 'NOTICE TO COMPLY', style: 'header3' },
          {},
          {
              style: 'tableExample',
              table: {
                  widths: ['*', '*', '*'],
                  body: [
                      [{ text: '' }, { text: '' }, { text: 'Bus.Control No.: ' + blpdNUm, style: 'headerright' }],
                      [{ text: '' }, { text: '' }, { text: BusinessName, style: 'headercenter' }],
                      [{ text: dateIssued, style: 'headerleft' }, { text: '' }, { text: '' }],
                      [{ text: BusinessName, style: 'headerleft' }, { text: '' }, { text: '' }],
                      [{ text: BusinessAddress, style: 'headerleft' }, { text: '' }, { text: '' }],
                  ]
              },
              layout: 'noBorders'
          },
          '\nSir/Madam,\n',
          {
            text: [
              {
                text: ' ', // Simulating an indent by adding spaces
                style: 'indent'
              },
               {
                text: ' ', // Simulating an indent by adding spaces
                style: 'indent'
              },
              {
                text: ' ', // Simulating an indent by adding spaces
                style: 'indent'
              },
              {
                text: ' ', // Simulating an indent by adding spaces
                style: 'indent'
              },
              {
                text: ' ', // Simulating an indent by adding spaces
                style: 'indent'
              },
              {
                text: ' ', // Simulating an indent by adding spaces
                style: 'indent'
              },
              {
                text: ' ', // Simulating an indent by adding spaces
                style: 'indent'
              },
              {
                text: ' ', // Simulating an indent by adding spaces
                style: 'indent'
              },
              {
                text: 'Upon verification of your Business Permit/s Business Tax Payment Certificates on file with this Office, the undersigned personnel noted the following deficiency/ies and or violations of the existing Local Revenue Code of the City Of San Fernando (P) viz:',
              }
            ],
            style: 'paragraph'
          },

          {
            // Custom checklist with underlines starts here
            stack: [
             createChecklistItem('Operating a business without Registration and a valid Mayor\'s Business Permit with the City Of San Fernando (P)', false),
              createChecklistItem('Operating a business without the approved additional requirements', false),
              createChecklistItem('Failure to renew the Mayor\'s Business Permit for the CY ________ 2015-2024 ________', true),
              // ... Add more checklist items using the createChecklistItem function
              createChecklistItem('Non-payment of amusement tax', false),
              createChecklistItem('Non-application of the termination, transfer and retirement of business', true),
              // ... More items
            ],
          },
          // ... insert the rest of your content here
      ],
      styles: {
          header: {
              fontSize: 10,
              alignment: 'center',
          },
          header3: {
              fontSize: 11,
              bold: true,
              alignment: 'center',
          },
          headerright: {
              fontSize: 10,
              bold: true,
              alignment: 'right',
          },
          headerleft: {
              fontSize: 10,
              bold: false,
              alignment: 'left',
          },
          headercenter: {
              fontSize: 10,
              bold: false,
              alignment: 'center',
          },
          paragraph: {
              alignment: 'justify',
              fontSize:11
          },
          checklistItem: {
            fontSize: 10,
            margin: [0, 5, 0, 10] // Adjust top and bottom margin for each item
          },
          checkedItem: {
            fontSize: 10,
            margin: [0, 5, 0, 10], // Adjust top and bottom margin for each item
            // decoration: 'underline'
          }
         
      },
      pageSize: 'LEGAL',
      pageMargins: [40, 60, 40, 60], // margin on all sides
  };



  // Helper function to create a checklist item with an underline
  function createChecklistItem(text, isChecked) {
    return {
      columns: [
        {
          // Add a checkbox or leave it empty
          width: 20,
          text: isChecked ? 'X' : '',
          style: isChecked ? 'checkedItem' : 'checklistItem',
          margin: [0, 2, 0, 0] // Center the checkbox vertically
        },
        {
          // The text for the item
          text: text,
          style: 'checklistItem',
          // decoration: 'underline',
          width: '*'
        }
      ],
      columnGap: 10
    };
  }
  // Assuming pdfMake is available in your environment
  pdfMake.createPdf(docDefinition).open(); // or .download() if you want to download directly
}
